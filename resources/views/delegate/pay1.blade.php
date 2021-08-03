@extends('delegate.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Delegate') }} :: {{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('delegate-pay-2') }}" method="post">
                            @csrf
                            <table border="1" align="center" width="800">
                                <tr>
                                    <th colspan="2">外卡收银台下单接口</th>
                                </tr>
                                <tr>
                                    <td width=200>商家ID</td>
                                    <td><input type='text' name='merchantId' oninput="changeOrderId()" id="merchantId"
                                               value='890002159'></td>
                                </tr>
                                <tr>
                                    <td width=200>商户订单号</td>
                                    <td><input type='text' name='requestId' id="requestId" value=''></td>
                                </tr>
                                <tr>
                                    <td width=200>订单金额</td>
                                    <td><input type='text' name='orderAmount' value='1.00'>(元)</td>
                                </tr>

                                <tr>
                                    <td width=200>订单币种</td>
                                    <td><input type='text' name='orderCurrency' value='USD'></td>
                                </tr>
                                <tr>
                                    <td width=200>语言</td>
                                    <td><input type='text' name='language' value='en'>选填</td>
                                </tr>
                                <tr>
                                    <td width=200>通知地址</td>
                                    <td><input type='text' name='notifyUrl' value='https://qa-sdk.5upay.com/icc/notify'
                                               size="80"></td>
                                </tr>
                                <tr>
                                    <td width=200>页面通知地址</td>
                                    <td><input type='text' name='callbackUrl'
                                               value='http://127.0.0.1:8000/en/delegate-pay-3'
                                               size="80"></td>
                                </tr>
                                <tr>
                                    <td width=200>终端号</td>
                                    <td>
                                        <input type='text' name='terminalNo' value='89000215900001'></td>

                                </tr>
                                <tr>
                                    <td width=200>备注</td>
                                    <td><input type='text' name='remark' value='备注'></td>
                                </tr>

                                <tr>
                                    <td width=200>订单类型</td>
                                    <td>
                                        <select id="orderType" name="orderType" onchange="changeOrderType();">
                                            <option value="STANDARD" selected>标准</option>
                                            <option value="RISK_SHIP">送货信息</option>
                                            <option value="RISK_BILL">账单信息</option>
                                            <option value="RISK_SHIP_BILL">送货和账单信息</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <table border="1" align="center" width="800">
                                <tr>
                                    <td colspan="2"><input type='submit' name='' value='提交' style="margin-top: 20px;">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>

        function changeOrderId() {
            document.getElementById("requestId").value = getNowFormatDate() + "-" + document.getElementById("merchantId").value + "-" + new Date().valueOf();
        }

        function getNowFormatDate() {
            var date = new Date();
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var strDate = date.getDate();
            if (month >= 1 && month <= 9) {
                month = "0" + month;
            }
            if (strDate >= 0 && strDate <= 9) {
                strDate = "0" + strDate;
            }
            var currentdate = year + month + strDate;
            return currentdate;
        }

        function changeOrderType() {
            var obj = document.getElementById("orderType");
            var index = obj.selectedIndex;
            var value = obj.options[index].value
            if ("STANDARD" === value) {
                document.getElementById("billInfo").style.display = 'none';
                document.getElementById("shipInfo").style.display = 'none';
            }

            if ("RISK_SHIP" === value) {
                document.getElementById("billInfo").style.display = 'none';
                document.getElementById("shipInfo").style.display = '';
            }

            if ("RISK_BILL" === value) {
                document.getElementById("billInfo").style.display = '';
                document.getElementById("shipInfo").style.display = 'none';
            }

            if ("RISK_SHIP_BILL" === value) {
                document.getElementById("billInfo").style.display = '';
                document.getElementById("shipInfo").style.display = '';
            }
        }
    </script>
@endpush
