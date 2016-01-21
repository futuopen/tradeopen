
# 富途开放-交易接口

for 第三方开发者

author: yosolin

[TOC]

<!-- MarkdownTOC -->

- [HTTP包头字段](#http包头字段)
- [测试环境](#测试环境)
- [Accounts API 账户接口](#accounts-api-账户接口)
    - [Get Account Detail 获取账户详情](#get-account-detail-获取账户详情)
    - [Get Account Cash 获取账户现金数据](#get-account-cash-获取账户现金数据)
    - [Get Account Portfolio 获取账户持仓](#get-account-portfolio-获取账户持仓)
- [Order API 订单接口](#order-api-订单接口)
    - [List Orders 获取订单列表](#list-orders-获取订单列表)
    - [List Trades 获取成交列表](#list-trades-获取成交列表)
    - [Place Order 下单](#place-order-下单)
    - [Change Order 修改订单](#change-order-修改订单)
    - [Cancel Order 撤单](#cancel-order-撤单)
- [Other API 其他接口](#other-api-其他接口)
    - [验证交易密码](#验证交易密码)
- [附录](#附录)
    - [接口中涉及的数据结构](#接口中涉及的数据结构)
    - [签名生成](#签名生成)

<!-- /MarkdownTOC -->

## <a name='http包头字段'></a>HTTP包头字段
| **Header** | **说明** | **示例值** | **必填** |
| ---- | ---- | ---- | ---- |
| Accept | 客户端能够接收的内容类型及版本 | application/vnd.futu5.openapi-v1+json | 是 |
| Content-Type | 请求内容的MIME类型 | application/vnd.futu5.openapi-v1+json | 是 |
| X-Futu-Oauth-Appid | 接入futu开放平台申请的appid | 1002 | 是 |
| X-Futu-Oauth-App-Account | 当前app登录的帐号，与授权填写的相同 | abc@qq.com | 是 |
| X-Futu-Oauth-Nonce | 随机数，用来防止重放 | 1234233 | 是 |
| X-Futu-Oauth-Accesstoken | 授权后的Accesstoken | skiqanIEk2wnsien2n+== | 是 |
| X-Futu-Oauth-Tradetoken | Tradetoken,<br />有交易操作（下单、改单、撤单）时需给 | MhObreMoofI9GjPe9hKmPtcKOY | 条件 |
| X-Futu-Oauth-Signature-Method | 签名方法，当前只支持HMAC-SHA1 | HMAC-SHA1 | 是 |
| X-Futu-Oauth-Timestamp | 当前时间戳 | 1450184260 | 是 |
| X-Futu-Oauth-Version | 认证协议版本，当前协议1.0 | 1.0 | 是 |
| X-Futu-Oauth-Signature | 签名，具体方法见附录 | rNnXNGGzae5IFgly%2BsTvVgVf | 是 |
| X-Futu-Oauth-Lang | 接收的语言 sc/tc/en (简体/繁体/英文) | sc | 是 |

*注：以下所有API返回result_code=0表示成功，其他为失败，失败信息见error_msg*

## <a name='测试环境'></a>测试环境
请将接口URL换成 https://`sandbox-`XXX，如https://sandbox-tradeopen.futu5.com/account

## <a name='accounts-api-账户接口'></a>Accounts API 账户接口

### <a name='get-account-detail-获取账户详情'></a>Get Account Detail 获取账户详情
#### 概述
获取账户详情

#### 描述
获取当前账户详情，如账户类型、状态、所属市场。

#### URL
https://tradeopen.futu5.com/account

#### HTTP method
GET

#### 请求参数
无

#### 返回数据
| **Property** | **Type** | **Description** |
| ---- | ---- | ---- |
| type | string | 账户类型。可能值: CASH(现金账户), MARGIN(保证金账户) |
| state | string | 账户状态。可能值: OPENING(正在开户), OPENED(已开户), CLOSED(已销户) |
| market | string | 所属市场。可能值：HK(港股市场)，US(美股市场)

#### 请求示例
    GET https://tradeopen.futu5.com/account

#### 示例返回 - JSON
    {
        "result_code": 0,
        "error_msg": "",
        "data": {
            "account": {
                "type": "CASH",
                "state": "OPENED",
                "market": "HK"
            }
        }
    }


### <a name='get-account-cash-获取账户现金数据'></a>Get Account Cash 获取账户现金数据
#### 概述
获取现金数据

#### 描述
获取当前账户现金数据

#### URL
https://tradeopen.futu5.com/account/cash

#### HTTP method
GET

#### 请求参数
无

#### 返回数据
| **Property** | **Type** | **Description** |
| ---- | ---- | ---- |
| cash | [CashPosition](#CashPosition) | 现金数据，CashPosition数据类型见后面说明 |

#### 请求示例
    GET https://tradeopen.futu5.com/account/cash

#### 示例返回 - JSON
    {
        "result_code": 0,
        "error_msg": "",
        "data": {
            "cash": {
                "balance": "10030",
                "debit_recover": "0",
                "drawable": "0",
                "frozen_power": "0",
                "loan_max": "100000",
                "power": "100000",
                "prev_asset_value": "10030",
                "prev_balance": "10030",
                "prev_stock_value": "0",
                "stock_margin_value": "153000",
                "stock_value": "310200",
                "today_profit": "0",
                "today_profit_ratio": 0,
                "today_settled": 0,
                "today_turnover": "0",
                "total_asset_value": "320230",
                "trade_count": 0,
                "type": "MARGIN",
            }
        }
    }

### <a name='get-account-portfolio-获取账户持仓'></a>Get Account Portfolio 获取账户持仓
#### 概述
获取持仓数据

#### 描述
获取当前账户股票持仓数据

#### URL
https://tradeopen.futu5.com/account/portfolio

#### HTTP method
GET

#### 请求参数
无

#### 返回数据
| **Property** | **Type** | **Description** |
| ---- | ---- | ---- |
| portfolio | [StockPosition](#StockPosition) | 股票持仓数据，StockPosition数据类型见后面说明 |

#### 请求示例
    GET https://tradeopen.futu5.com/account/portfolio

#### 示例返回 - JSON
    {
        "result_code": 0,
        "error_msg": "",
        "data": {
            "portfolio": [
                {
                    "code": 90008,
                    "name": "Futu&stock 8",
                    "cost_price": "23",
                    "cost_price_invalid": "",
                    "power": 8000,
                    "profit": "7600",
                    "profit_ratio": 0.041304347826087,
                    "profit_ratio_invalid": "",
                    "quantity": 8000,
                    "today_long_avg_price": "0",
                    "today_long_shares": 0,
                    "today_long_turnover": "0",
                    "today_profit": "0",
                    "today_short_avg_price": "0",
                    "today_short_shares": 0,
                    "today_short_turnover": "0",
                    "today_turnover": "0",
                    "value": "191600",
                    "value_price": "23.95"
                },
                {
                    "code": 90009,
                    "name": "Futu&stock 9",
                    "cost_price": "23",
                    "cost_price_invalid": "",
                    "power": 8000,
                    "profit": "7600",
                    "profit_ratio": 0.041304347826087,
                    "profit_ratio_invalid": "",
                    "quantity": 1000,
                    "today_long_avg_price": "0",
                    "today_long_shares": 0,
                    "today_long_turnover": "0",
                    "today_profit": "0",
                    "today_short_avg_price": "0",
                    "today_short_shares": 0,
                    "today_short_turnover": "0",
                    "today_turnover": "0",
                    "value": "41234",
                    "value_price": "132.95"
                }
            ]
        }
    }

## <a name='order-api-订单接口'></a>Order API 订单接口

### <a name='list-orders-获取订单列表'></a>List Orders 获取订单列表
#### 概述
获取订单数据

#### 描述
获取当前用户当前账户订单数据

#### URL
https://tradeopen.futu5.com/orders

#### HTTP method
GET

#### 请求参数
| **Property** | **Type** | **Required?** | **Description** |
| ---- | ---- | ---- | ---- |
| date_begin | string | optional | 起始日期，如20151101，默认为当天日期 |
| date_end | string | optional | 结束日期，如20151105，默认为当天日期 |

#### 返回数据
| **Property** | **Type** | **Description** |
| ---- | ---- | ---- |
| order | [Order](#Order) | 订单列表，Order数据类型见后面说明 |

#### 请求示例
    GET https://tradeopen.futu5.com/orders

#### 示例返回 - JSON
    {
        "result_code": 0,
        "error_msg": "",
        "data": {
            "order": [
                {
                    "order_id": "1234561",
                    "avg_price": "142.5",
                    "code": "00700",
                    "name": "腾讯",
                    "created": 1446618508,
                    "enable": 1,
                    "last_err": 0,
                    "matched_quantity": 0,
                    "modified": 1446618536,
                    "price": "142",
                    "quantity": 1000,
                    "side": "SELL",
                    "state": 1,
                    "type": "E",
                    "last_err_text": "",
                    "side_text": "卖",
                    "type_text": "普通",
                    "state_text": "等待成交"
                },
                {
                    "order_id": "1234562",
                    "avg_price": "12.5",
                    "code": "02208",
                    "name": "金风科技",
                    "created": 1446618608,
                    "enable": 1,
                    "last_err": 0,
                    "matched_quantity": 0,
                    "modified": 1446618636,
                    "price": "13",
                    "quantity": 1000,
                    "side": "SELL",
                    "state": 1,
                    "type": "E",
                    "last_err_text": "",
                    "side_text": "买",
                    "type_text": "普通",
                    "state_text": "已成交"
                }
            ]
        }
    }

### <a name='list-trades-获取成交列表'></a>List Trades 获取成交列表
#### 概述
获取账户今日成交列表

#### 描述
获取账户今日成交列表，一个订单可能会对应多个成交。成交可能没有对应的订单，比如线下的成交。

#### URL
https://tradeopen.futu5.com/trades

#### HTTP method
GET

#### 请求参数
无

#### 返回数据
| **Property** | **Type** | **Description** |
| ---- | ---- | ---- |
| trade | [Trade](#Trade) | 成交列表，Trade数据类型见后面说明 |

#### 请求示例
    GET https://tradeopen.futu5.com/trades

#### 示例返回 - JSON
    {
        "result_code": 0,
        "error_msg": "",
        "data": {
            "trade": [
                {
                    "code": 90008,
                    "name": "Futu&stock 8",
                    "counter_broker_id": 5001,
                    "created": 1446620585,
                    "order_created": 1446620585,
                    "order_id": "23452345",
                    "order_modified": 1446620585,
                    "price": "30.5",
                    "quantity": 200,
                    "side": "SELL",
                    "id": 16,
                    "side_text": "卖"
                }
            ]
        }
    }

### <a name='place-order-下单'></a>Place Order 下单
#### 概述
下订单

#### 描述
在当前账户下订单

#### URL
https://tradeopen.futu5.com/orders

#### HTTP method
POST

#### 请求参数
| **Property** | **Type** | **Required?** | **Description** |
| ---- | ---- | ---- | ---- |
| code | string | required | 股票代码，如00700（港股账户），或BABA（美股账户） |
| quantity | integer | required | 数量，如1000 |
| price | double | required | 价格，如150.5。若为美股MARKET市价单，请填0 |
| side | string | required | 方向。BUY买入，SELL卖出 |
| type | string | required | 订单类型。港股和美股不同。港股：E普通订单（增强限价单），A竞价单，I竞价限价单；美股：LIMIT限价单，MARKET市价单 |

#### 返回数据
| **Property** | **Type** | **Description** |
| ---- | ---- | ---- |
| order_id | string | 订单ID |

#### 请求示例
    POST https://tradeopen.futu5.com/orders

**请求参数 - JSON**

    {
        "code": "00700",
        "quantity": 1000,
        "price": "150.5",
        "side": "SELL",
        "type": "E"
    }

#### 示例返回 - JSON
    {
        "result_code": 0,
        "error_msg": "",
        "data": {
            "order_id": "123412"
        }
    }

### <a name='change-order-修改订单'></a>Change Order 修改订单
#### 概述
修改订单

#### 描述
对还没成交的订单做修改，仅可修改订单数量和价格

#### URL
https://tradeopen.futu5.com/orders/{order_id}

#### HTTP method
PUT

#### 请求参数
| **Property** | **Type** | **Required?** | **Description** |
| ---- | ---- | ---- | ---- |
| order_id | string | required | 订单ID |
| action | integer | required | 填1 |
| quantity | integer | required | 数量 |
| price | double | required | 价格 |

#### 返回数据
空

#### 请求示例
    PUT https://tradeopen.futu5.com/orders/123412

**请求参数 - JSON**

    {
        "action": 1,
        "quantity": 1000,
        "price": 160.5
    }

#### 示例返回 - JSON
成功：

    {
        "result_code": 0,
        "error_msg": ""
    }

失败：

    {
        "result_code": 100,
        "error_msg": "资金不足"
    }

### <a name='cancel-order-撤单'></a>Cancel Order 撤单
#### 概述
取消订单

#### 描述
对还没成交的订单撤单

#### URL
https://tradeopen.futu5.com/orders/{order_id}

#### HTTP method
PUT

#### 请求参数
| **Property** | **Type** | **Required?** | **Description** |
| ---- | ---- | ---- | ---- |
| order_id | string | required | 订单ID |
| action | integer | required | 填0 |

#### 返回数据
空

#### 请求示例
    PUT https://tradeopen.futu5.com/orders/123412

**请求参数 - JSON**

    {
        "action": 0
    }

#### 示例返回 - JSON
成功：

    {
        "result_code": 0,
        "error_msg": ""
    }

失败：

    {
        "result_code": 111,
        "error_msg": "非交易时间"
    }

## <a name='other-api-其他接口'></a>Other API 其他接口
### <a name='验证交易密码'></a>验证交易密码
#### 概述
验证交易密码，获取tradetoken

#### 描述
验证交易密码，获取tradetoken

#### URL
https://openapi.futu5.com/auth_trade_pswd

#### HTTP method
POST

#### 请求参数
| **Property** | **Type** | **Required?** | **Description** |
| ---- | ---- | ---- | ---- |
| trade_pswd | string | required | 交易密码 |

#### 返回数据
| **Property** | **Type** | **Description** |
| ---- | ---- | ---- |
| trade_token | string | tradetoken |

#### 请求示例
    POST https://sandbox-openapi.futu5.com/auth_trade_pswd

**请求参数 - JSON**

    {
        "trade_pswd": "xxxxxx"
    }

#### 示例返回 - JSON
成功：

    {
        "result_code": 0,
        "error_msg": "",
        "data": {
            "trade_token": "Ij0UYMooWbBqvyNllTXAysfA1KOutFbzQLwCmUK2GiAaOUmlFWEH_Cg_uf7pHwhgOcf0-MLSurDZgoL4F73gxg=="
        }
    }

失败：

    {
        "result_code": 1102,
        "error_msg": "交易密码错误"
    }

## <a name='附录'></a>附录

### <a name='接口中涉及的数据结构'></a>接口中涉及的数据结构
##### <a name='CashPosition'></a>现金仓位 CashPosition

说明：表示客户资产的现金部分。以下金额单位都为0.001元，如10025000表示10025元

字段：

| 名称 | 说明 |
| ---- | ---- |
| balance | 现金结余 |
| debit_recover | 欠款 |
| drawable | 可提金额（暂时不要用这个金额） |
| loan_max | 可使用的最大信贷额度 |
| power | 当前最大购买力 |
| frozen_power | 已冻结购买力。由于下买单等暂时冻结的购买力 |
| prev_asset_value | 上个交易日结算后的资产市值 |
| prev_balance | 上个交易日结算后的现金结余 |
| prev_stock_value | 上个交易日结算后的股票市值 |
| stock_margin_value | 股票抵押额 |
| stock_value | 股票市值 |
| today_profit | 今日交易的盈亏额 |
| today_profit_ratio | 今日交易的盈亏比例。0.1表示10% |
| today_settled | 今天是否已经结算。0表示未结算，1表示已结算 |
| today_turnover | 今日成交额 |
| total_asset_value | 资产总额，含现金和股票市值 |
| trade_count | 今日成交笔数 |

例如:

    {
        "balance": "10030",
        "debit_recover": "0",
        "drawable": "0",
        "frozen_power": "0",
        "loan_max": "100000",
        "power": "100000",
        "prev_asset_value": "10030",
        "prev_balance": "10030",
        "prev_stock_value": "0",
        "stock_margin_value": "153000",
        "stock_value": "310200",
        "today_profit": "0",
        "today_profit_ratio": 0,
        "today_settled": 0,
        "today_turnover": "0",
        "total_asset_value": "320230",
        "trade_count": 0
    }


##### <a name='StockPosition'></a>股票仓位 StockPosition

说明：表示客户资产的股票部分

字段：

| 名称 | 说明 |
| ---- | ---- |
| code | 股票代码 |
| name | 股票名称。使用了请求中指定的语言 |
| cost_price | 成本价（以何种价格卖出才能使本次建仓不亏本） |
| cost_price_invalid | 成本价非法。该字段不为空时表示成本价不可用 |
| on_hold | 由于卖单而暂时冻结的可卖股数 |
| power | 可卖股数 |
| quantity | 持有股数 |
| profit | 自建仓以来的总盈亏额 |
| profit_ratio | 自建仓以来的总盈亏比例 |
| profit_ratio_invalid | 总盈亏比例非法。该字段不为空时表示总盈亏额不可用 |
| today_long_avg_price | 今日平均买入价 |
| today_long_shares | 今日买入股数 |
| today_long_turnover | 今日买入成交额 |
| today_profit | 今日盈亏额。今日不管建仓清仓多少次，所有在这只股票上操作的盈亏额。 |
| today_short_avg_price | 今日平均卖出价 |
| today_short_shares | 今日卖出股数 |
| today_short_turnover | 今日卖出成交额 |
| today_turnover | 今日成交额 |
| value | 当前市值 |
| value_price | 用于计算市值的价格 |

例如：

    {
        "code": 90008,
        "name": "Futu&stock 8",
        "cost_price": "23",
        "cost_price_invalid": "",
        "power": 8000,
        "profit": "7600",
        "profit_ratio": 0.041304347826087,
        "profit_ratio_invalid": "",
        "quantity": 8000,
        "today_long_avg_price": "0",
        "today_long_shares": 0,
        "today_long_turnover": "0",
        "today_profit": "0",
        "today_short_avg_price": "0",
        "today_short_shares": 0,
        "today_short_turnover": "0",
        "today_turnover": "0",
        "value": "191600",
        "value_price": "23.95"
    }


##### <a name='Order'></a>订单 Order

说明：表示一个订单

字段：

| 名称 | 意义 |
| ---- | ---- |
| avg_price | 平均成交价 |
| code | 股票代码 |
| name | 股票名称。使用了请求中指定的语言 |
| create_time | 创建时间（时间戳） |
| update_time | 最近一次修改时间（时间戳） |
| enable | 生效自段（暂不用管） |
| last_err | 订单最近一次发生的错误码。0为没有错误。 具体的错误文本可以使用下面的last_err_text展示给客户 |
| matched_quantity | 已成交股数 |
| order_id | 订单号，可以唯一标记该用户的一个订单 |
| price | 订单价格 |
| quantity | 订单数量 |
| side | 方向。字符串。BUY：买入， SELL：卖出。可以使用下面的side_text字段展示给客户。 |
| state | 状态。整数。可能的取值有：<br />0正在提交，1正常，2已撤单，3已删除，4下单失败，5 等待开盘。<br />可以使用下面的state_text字段展示给客户。 |
| type | 订单类型。字符串，必须是一个字符。 可能的取值如下：<br /> E:增强限价单(Enhanced Limit)，A:竞价单(Auction)，I:竞价限价单(Auction Limit)，L:限价单(Limit)，S:特别限价单(Special Limit)。LIMIT:限价单，MARKET:市价单<br />对港股只有E\A\I三种订单允许客户输入，美股只有LIMIT和MARKET<br />可以使用下面的type_text字段展示给客户。|
| last_err_text | 对应上面last_err字段的展示文本，文本使用的语言由请求参数决定 |
| side_text | 对应上面side字段的展示文本，文本使用的语言由请求参数决定 |
| type_text | 对应上面type字段的展示文本，文本使用的语言由请求参数决定 |
| state_text | 对应上面state字段的展示文本，文本使用的语言由请求参数决定 |

例如：

    {
        "avg_price": "0",
        "code": 90003,
        "name": "Futu&stock 3",
        "create_time": 1446618508,
        "enable": 1,
        "last_err": 0,
        "matched_quantity": 0,
        "update_time": 1446618536,
        "order_id": "13253",
        "price": "10.5",
        "quantity": 1000,
        "side": "SELL",
        "state": 1,
        "type": "E",
        "last_err_text": "",
        "side_text": "卖",
        "type_text": "普通",
        "state_text": "等待成交"
    }

##### <a name='Trade'></a>成交 Trade

说明：表示一个成交。一个订单可能会对应多笔成交。

字段：

| 名称 | 意义 |
| ---- | ---- |
| code | 股票代码 |
| name | 股票名称。使用了请求中指定的语言 |
| counter_broker_id | 成交对手经纪号 |
| create_time | 创建时间（时间戳） |
| order_created | 所属订单的创建时间（时间戳） |
| order_id | 所属订单ID。有可能为0，表示该成交不属于任何一个订单。 |
| order_modified | 所属订单的修改时间（时间戳） |
| price | 成交价格 |
| quantity | 成交数量 |
| side | 方向。字符串。BUY：买入， SELL：卖出。可以使用下面的side_text字段展示给客户。 |
| id | 成交ID，唯一标志了一笔成交 |
| side_text | 对应上面side字段的展示文本，文本使用的语言由请求参数决定 |

例如：

    {
        "code": 90008,
        "name": "Futu&stock 8",
        "counter_broker_id": 5001,
        "create_time": 1446620585,
        "order_created": 1446620585,
        "order_id": "345673654",
        "order_modified": 1446620585,
        "price": "30.5",
        "quantity": 200,
        "side": "SELL",
        "id": 16,
        "side_text": "卖"
    }

### <a name='签名生成'></a>签名生成
- 签名的Key：申请分配的AppSecret和授权后的accessToken，由”&”字符串拼接，AppSecret+”&”+accessToken
- 签名包头字段：

| 需要签名的HTTP头字段 | 接口 |
| --- | --- |
| X-Futu-Oauth-Appid | 所有 |
| X-Futu-Oauth-App-Account | 所有 |
| X-Futu-Oauth-Appid | 所有 |
| X-Futu-Oauth-App-Account | 所有 |
| X-Futu-Oauth-Nonce | 所有 |
| X-Futu-Oauth-Lang | 所有 |
| X-Futu-Oauth-Accesstoken | 所有 |
| X-Futu-Oauth-Signature-Method | 所有 |
| X-Futu-Oauth-Timestamp | 所有 |
| X-Futu-Oauth-Version | 所有 |
| X-Futu-Oauth-Tradetoken | 下单、改单、撤单 |

- 签名明文：包头与包体字段合并后的key-value字典，排序后由”&”连接
- 签名算法：HMAC-SHA1

【PHP示例代码】

    /**
     * 生成签名
     *
     * @param $sig_header   array   需要签名的HTTP头字段
     * @param $body         array   HTTP包体。可能为NULL，比如GET请求时
     * @param $url          string  url
     * @param $method       string  http method，比如 'GET', 'POST' 或 'PUT'
     * @param $app_secret   string  签名密钥
     * @param $accesstoken  string  accesstoken
     *
     * @return string 签名字符串
     */
    function genSignature($sig_header, $body, $url, $method, $app_secret, $accesstoken)
    {
        // 合并header与body
        $sig_fields = $sig_header;
        foreach ($body as $key => $val)
            $sig_fields[$key] = $val;

        // 按KEY排序
        ksort($sig_fields);

        // 拼接成字符串
        $sig_plain = $method . '&' . urlencode($url);
        foreach($sig_fields as $key => &$val)
            $sig_plain .= '&' . $key . '=' . urlencode($val);

        // 签名KEY
        $sig_key = $app_secret . '&' . $accesstoken;

        // 使用HMAC-SHA1算法签名
        $sig = base64_encode( hash_hmac('sha1', $sig_plain, $sig_key, true) );

        return $sig;
    }
