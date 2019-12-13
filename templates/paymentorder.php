<!DOCTYPE html>
<html lang="en">

<head>
    <script src="js/paymentorder.js"></script>
    <title>hosted view</title>
</head>

<body>
    <div id="paymentMenu"></div>
</body>

<script src="<?php echo $href ?>"></script>
<script language="javascript">
"use strict";

payex.hostedView.paymentMenu(configpaymentorder).open();
//payex.hostedView.paymentMenu().refresh();
//payex.hostedView.paymentMenu().close();
</script>

</html>