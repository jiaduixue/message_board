<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * 会员 + 支付功能
 */
class MemberController extends Controller
{
    public $enableCsrfValidation = false;

    /** 会员套餐配置（真实项目可放数据库/配置文件） */
    const LEVELS = [
        'monthly'   => ['level_name' => '月度会员', 'amount' => 25.00,  'days' => 30,  'points' => 100],
        'quarterly' => ['level_name' => '季度会员', 'amount' => 68.00,  'days' => 90,  'points' => 350],
        'yearly'    => ['level_name' => '年度会员', 'amount' => 198.00, 'days' => 365, 'points' => 1500],
    ];

    const PAY_TYPES = ['wechat', 'alipay'];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        return $behaviors;
    }

    /**
     * 会员套餐列表（点击会员按钮时展示）
     * GET /member/plans
     */
    public function actionPlans()
    {
        $list = [];
        foreach (self::LEVELS as $code => $cfg) {
            $list[] = [
                'level_code' => $code,
                'level_name' => $cfg['level_name'],
                'amount'     => number_format($cfg['amount'], 2, '.', ''),
                'days'       => $cfg['days'],
                'points'     => $cfg['points'],
            ];
        }
        return ['code' => 200, 'message' => 'success', 'data' => ['list' => $list]];
    }

    /**
     * 创建支付订单（选套餐 + 选微信/支付宝）
     * POST /member/create-order  {"level_code":"monthly","pay_type":"wechat"}
     */
    public function actionCreateOrder()
    {
        $uid       = Yii::$app->user->id;
        $levelCode = trim((string)Yii::$app->request->post('level_code', ''));
        $payType   = trim((string)Yii::$app->request->post('pay_type', ''));

        if (!isset(self::LEVELS[$levelCode])) {
            throw new BadRequestHttpException('会员等级不存在');
        }
        if (!in_array($payType, self::PAY_TYPES, true)) {
            throw new BadRequestHttpException('支付方式只能是 wechat / alipay');
        }

        $level   = self::LEVELS[$levelCode];
        $orderNo = 'MB' . date('YmdHis') . mt_rand(1000, 9999);

        // —— 写入支付表（待支付）——
        Yii::$app->db->createCommand()->insert('payment_orders', [
            'order_no'    => $orderNo,
            'customer_id' => $uid,
            'pay_type'    => $payType,
            'biz_type'    => 'member',
            'level_code'  => $levelCode,
            'level_name'  => $level['level_name'],
            'amount'      => $level['amount'],
            'status'      => 0,
        ])->execute();

        // ===== 真实环境：这里调微信统一下单 / 支付宝预创建接口，拿到真正支付参数 =====
        // 演示环境返回模拟参数，前端据此"拉起支付"
        $payParams = ($payType === 'wechat')
            ? ['appId' => 'wx_demo', 'timeStamp' => (string)time(),
               'nonceStr' => md5(uniqid()), 'package' => 'prepay_id=demo_' . $orderNo,
               'signType' => 'MD5', 'paySign' => 'demo_sign']
            : ['trade_str' => 'demo_alipay_string_' . $orderNo];

        return [
            'code'    => 200,
            'message' => '订单创建成功，请拉起支付',
            'data'    => [
                'order_no'   => $orderNo,
                'amount'     => number_format($level['amount'], 2, '.', ''),
                'pay_type'   => $payType,
                'pay_params' => $payParams,
            ],
        ];
    }

    /**
     * 支付成功回调（演示：模拟微信/支付宝异步通知）
     * POST /member/pay-success  {"order_no":"MB...","trade_no":"可选"}
     * 事务保证：改支付单 + 生成/续期会员记录 要么都成功要么都失败
     */
    public function actionPaySuccess()
    {
        $uid     = Yii::$app->user->id;
        $orderNo = trim((string)Yii::$app->request->post('order_no', ''));
        if ($orderNo === '') {
            throw new BadRequestHttpException('缺少 order_no');
        }
        $tradeNo = trim((string)Yii::$app->request->post('trade_no', ''))
                 ?: ('DEMO' . date('YmdHis') . mt_rand(100, 999));

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            // 行锁，防并发重复回调
            $order = $db->createCommand(
                'SELECT * FROM payment_orders WHERE order_no = :no AND customer_id = :uid FOR UPDATE',
                [':no' => $orderNo, ':uid' => $uid]
            )->queryOne();

            if (!$order) {
                throw new NotFoundHttpException('订单不存在');
            }
            if ((int)$order['status'] === 1) {
                $transaction->rollBack();
                return ['code' => 200, 'message' => '订单已支付过，无需重复处理', 'data' => null];
            }
            if ((int)$order['status'] !== 0) {
                throw new BadRequestHttpException('订单状态异常，无法支付');
            }

            $now   = time();
            $level = self::LEVELS[$order['level_code']];

            // 1) 支付单 → 已支付
            $db->createCommand()->update('payment_orders', [
                'status'   => 1,
                'trade_no' => $tradeNo,
                'paid_at'  => date('Y-m-d H:i:s'),
            ], ['id' => $order['id']])->execute();

            // 2) 生成 / 续期 会员记录
            $member = (new Query())->from('customer_member')
                ->where(['customer_id' => $uid])->one();

            if ($member) {
                // 未过期 → 在原到期时间上续期；已过期 → 从现在重新算
                $base      = (strtotime($member['expire_date']) > $now) ? strtotime($member['expire_date']) : $now;
                $newExpire = date('Y-m-d H:i:s', $base + $level['days'] * 86400);

                $db->createCommand()->update('customer_member', [
                    'level_code'   => $order['level_code'],
                    'level_name'   => $order['level_name'],
                    'points'       => (int)$member['points'] + $level['points'],
                    'total_points' => (int)$member['total_points'] + $level['points'],
                    'status'       => 1,
                    'expire_date'  => $newExpire,
                    'updated_at'   => date('Y-m-d H:i:s'),
                ], ['id' => $member['id']])->execute();
            } else {
                $db->createCommand()->insert('customer_member', [
                    'customer_id'  => $uid,
                    'level_code'   => $order['level_code'],
                    'level_name'   => $order['level_name'],
                    'points'       => $level['points'],
                    'total_points' => $level['points'],
                    'status'       => 1,
                    'join_date'    => date('Y-m-d H:i:s'),
                    'expire_date'  => date('Y-m-d H:i:s', $now + $level['days'] * 86400),
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ])->execute();
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return [
            'code'    => 200,
            'message' => '支付成功，会员已生效 🎉',
            'data'    => [
                'order_no' => $orderNo,
                'member'   => (new Query())->from('customer_member')->where(['customer_id' => $uid])->one(),
            ],
        ];
    }

    /**
     * 我的会员信息  GET /member/info
     */
    public function actionInfo()
    {
        $uid    = Yii::$app->user->id;
        $member = (new Query())->from('customer_member')->where(['customer_id' => $uid])->one();

        if (!$member) {
            return ['code' => 200, 'message' => 'success', 'data' => ['is_member' => 0, 'member' => null]];
        }

        $active = ((int)$member['status'] === 1) && (strtotime($member['expire_date']) > time());

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => ['is_member' => $active ? 1 : 0, 'member' => $member],
        ];
    }

    /**
     * 我的支付记录  GET /member/payments?page=1&pageSize=10
     */
    public function actionPayments()
    {
        $uid      = Yii::$app->user->id;
        $page     = max(1, (int)Yii::$app->request->get('page', 1));
        $pageSize = min(50, max(1, (int)Yii::$app->request->get('pageSize', 10)));

        $query = (new Query())->from('payment_orders')
            ->where(['customer_id' => $uid])
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC]);

        $total     = (int)$query->count();
        $list      = $query->limit($pageSize)->offset(($page - 1) * $pageSize)->all();
        $statusMap = [0 => '待支付', 1 => '已支付', 2 => '已取消', 3 => '已退款'];
        $payMap    = ['wechat' => '微信支付', 'alipay' => '支付宝'];

        foreach ($list as &$row) {
            $row['status_text']   = $statusMap[(int)$row['status']] ?? '未知';
            $row['pay_type_text'] = $payMap[$row['pay_type']] ?? $row['pay_type'];
        }
        unset($row);

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => ['total' => $total, 'page' => $page, 'pageSize' => $pageSize, 'list' => $list],
        ];
    }
}