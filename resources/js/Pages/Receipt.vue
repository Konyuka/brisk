<script setup>
// import { Head, Link } from "@inertiajs/inertia-vue3";
// import { ref } from "vue";
// import { Inertia } from "@inertiajs/inertia";

const generatePDF = () =>
{ 
    var doc = new jsPDF();
    var specialElementHandlers = {
        '#editor': function (element, renderer) {
            return true;
        }
    };

    doc.fromHTML($('#htmlContent').html(), 2, 2, {
        'width': 44,
        // 'height': "44mm",
        'elementHandlers': specialElementHandlers
    });
    doc.save('sample_file.pdf');
}

defineProps({
//   canLogin: Boolean,
});

</script>

<template>
<!-- <div>
    <div id="invoice-POS">


            <div id="htmlContent">
               

                <div>
                  <h1 style="font-size: 20.0pt; padding-top: 3pt;padding-left: 54pt;text-indent: 0pt;text-align: center;">Brisk International</h1>
                  <div id="mid">
                    <div style="font-size: 18.0px">
                        <p> 
                            Address : street city, state 0000<br>
                            Email   : JohnDoe@gmail.com<br>
                            Phone   : 5555555555<br>
                        </p>
                    </div>
                  </div> 

                  <p style="padding-top: 2pt;padding-left: 114pt;text-indent: 0pt;text-align: center;">43 Manchester Road</p>
                  <p style="padding-left: 177pt;text-indent: 0pt;line-height: 111%;text-align: center;">12480 Brisbane Australia</p>
                  <h2 style="padding-left: 114pt;text-indent: 0pt;line-height: 19pt;text-align: center;">617-3236-6207</h2>
                  <p style="text-indent: 0pt;text-align: left;"><br/>
                  </p>       
                </div>


                           
                <div id="bot">

                                <div id="table">
                                    <table>
                                        <tr class="tabletitle">
                                            <td class="item"><h2>Item</h2></td>
                                            <td class="Hours"><h2>Qty</h2></td>
                                            <td class="Rate"><h2>Sub Total</h2></td>
                                        </tr>

                                        <tr class="service">
                                            <td class="tableitem"><p class="itemtext">Communication</p></td>
                                            <td class="tableitem"><p class="itemtext">5</p></td>
                                            <td class="tableitem"><p class="itemtext">$375.00</p></td>
                                        </tr>

                                        <tr class="tabletitle">
                                            <td></td>
                                            <td class="Rate"><h2>tax</h2></td>
                                            <td class="payment"><h2>$419.25</h2></td>
                                        </tr>

                                        <tr class="tabletitle">
                                            <td></td>
                                            <td class="Rate"><h2>Total</h2></td>
                                            <td class="payment"><h2>$3,644.25</h2></td>
                                        </tr>

                                    </table>
                                </div>
                                <div id="legalcopy">
                                    <p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days; please process this invoice within that time. There will be a 5% interest charge per month on late invoices. 
                                    </p>
                                </div>

                </div>
            </div>

    </div>
    <button @click="generatePDF" clas="mt-10"> Download PDF </button>
</div> -->


<div id="htmlContent">
    <h1 style="padding-top: 3pt;padding-left: 114pt;text-indent: 0pt;text-align: center;">Th Lone Pine</h1>
    <p style="padding-top: 2pt;padding-left: 114pt;text-indent: 0pt;text-align: center;">43 Manchester Road</p>
    <p style="padding-left: 177pt;text-indent: 0pt;line-height: 111%;text-align: center;">12480 Brisbane Australia</p>
    <h2 style="padding-left: 114pt;text-indent: 0pt;line-height: 19pt;text-align: center;">617-3236-6207</h2>
    <p style="text-indent: 0pt;text-align: left;"><br/>
    </p>
    <table style="padding-left: 460pt" >
        <tr style="height:24pt">
            <td style="width:99pt"><p class="s1" style="padding-left: 4pt;text-indent: 0pt;line-height: 22pt;text-align: left;">Invoice</p></td>
            <td style="width:213pt"><p class="s1" style="padding-left: 6pt;text-indent: 0pt;line-height: 22pt;text-align: left;">08000008</p></td>
            <td style="width:117pt"><p class="s1" style="padding-left: 10pt;text-indent: 0pt;line-height: 22pt;text-align: left;">09/04/0 8</p></td>
        </tr>
        <tr style="height:23pt">
            <td style="width:99pt"><p class="s1" style="padding-left: 4pt;text-indent: 0pt;line-height: 21pt;text-align: left;">Table</p></td>
            <td style="width:213pt"><p class="s1" style="padding-left: 6pt;text-indent: 0pt;line-height: 21pt;text-align: left;">25</p></td>
            <td style="width:117pt"><p class="s1" style="padding-left: 47pt;text-indent: 0pt;line-height: 21pt;text-align: left;">12:45</p></td>
        </tr>
        <tr style="height:51pt">
            <td style="width:312pt" colspan="2"><p style="text-indent: 0pt;text-align: left;"><br/></p><p class="s1" style="padding-left: 23pt;text-indent: 0pt;text-align: left;">2 Carlsberg Bottle</p></td>
            <td style="width:114pt"><p style="text-indent: 0pt;text-align: left;"><br/></p><p class="s1" style="text-indent: 0pt;text-align: right;">16.00</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p>
            </td>
        </tr>
        <tr style="height:29pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 2pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">3 Heineken Draft Standard</p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 3pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">24.60</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:28pt"><td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 2pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">1 Heineken Draft Half Liter</p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 2pt;text-indent: 0pt;text-align: right;">15. 20</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:29pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 2pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">2 Carlsberg Bucket (5 bottles)</p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 3pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">80.00</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:29pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 2pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">4 Grilled Chicken Breast</p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 3pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">74.00</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:29pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 2pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">3 Sirloin Steak</p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 2pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">96.00</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:29pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 2pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">1 Coke</p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 3pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">3.50</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:33pt">
            <td style="width:312pt;border-bottom-style:solid;border-bottom-width:3pt" colspan="2"><p class="s1" style="padding-top: 2pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">5 Ice Cream</p></td>
            <td style="width:114pt;border-bottom-style:solid;border-bottom-width:3pt"><p class="s1" style="padding-top: 2pt;text-indent: 0pt;text-align: right;">18.00</p></td>
            <td style="width:3pt;border-bottom-style:solid;border-bottom-width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:32pt">
            <td style="width:312pt;border-top-style:solid;border-top-width:3pt" colspan="2"><p class="s1" style="padding-top: 7pt;padding-left: 86pt;text-indent: 0pt;text-align: left;">Subtotal</p></td>
            <td style="width:114pt;border-top-style:solid;border-top-width:3pt"><p class="s1" style="padding-top: 7pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">327.30</p></td>
            <td style="width:3pt;border-top-style:solid;border-top-width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:25pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 1pt;padding-left: 86pt;text-indent: 0pt;line-height: 22pt;text-align: left;">Sales/Gov Tax - 5°/o</p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 1pt;padding-right: 2pt;text-indent: 0pt;line-height: 22pt;text-align: right;">16.36</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:40pt">
            <td style="width:312pt;border-top-style:solid;border-top-width:3pt" colspan="2"><p class="s2" style="padding-top: 5pt;padding-left: 86pt;text-indent: 0pt;text-align: left;">GRAND TOTAL</p></td>
            <td style="width:114pt;border-top-style:solid;border-top-width:3pt"><p class="s1" style="padding-top: 7pt;padding-right: 3pt;text-indent: 0pt;text-align: right;">376.40</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:35pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 8pt;text-indent: 0pt;text-align: left;">Thank you and       <span class="s3">Cash</span></p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 10pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">400. 00</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:26pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="text-indent: 0pt;line-height: 24pt;text-align: left;">see you again!        <span class="s4">Change</span></p></td>
            <td style="width:114pt"><p class="s1" style="padding-top: 1pt;padding-right: 2pt;text-indent: 0pt;text-align: right;">23.60</p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
        <tr style="height:25pt">
            <td style="width:312pt" colspan="2"><p class="s1" style="padding-top: 1pt;text-indent: 0pt;line-height: 21pt;text-align: left;">John</p></td>
            <td style="width:114pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
            <td style="width:3pt"><p style="text-indent: 0pt;text-align: left;"><br/></p></td>
        </tr>
    </table>
    <p style="text-indent: 0pt;text-align: left;"><br/></p>
</div>

<button @click="generatePDF" clas="mt-10"> Download PDF </button>
</template>

<style scoped>
#invoice-POS {
  padding: 2mm;
  margin: 0 auto;
  width: 44mm;
  background: #fff;
}

* {margin:0; padding:0; text-indent:0; }
 h1 { color: black; font-family:Arial, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 28.5pt; }
 p { color: black; font-family:Arial, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 19.5pt; margin:0pt; }
 h2 { color: black; font-family:"Times New Roman", serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 19.5pt; }
 .s1 { color: black; font-family:Arial, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 19.5pt; }
 .s2 { color: black; font-family:Arial, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 18.5pt; }
 .s3 { color: black; font-family:Arial, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 19.5pt; vertical-align: -1pt; }
 .s4 { color: black; font-family:Arial, sans-serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 19.5pt; vertical-align: -3pt; }
 table, tbody {vertical-align: top; overflow: visible; }
</style>


<!-- #invoice-POS {
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding: 2mm;
  margin: 0 auto;
  width: 44mm;
  background: #fff;
} -->

<!-- <style scoped>
#invoice-POS ::selection {
  background: #f31544;
  color: #fff;
}
#invoice-POS ::moz-selection {
  background: #f31544;
  color: #fff;
}
#invoice-POS h1 {
  font-size: 1.5em;
  color: #222;
}
#invoice-POS h2 {
  font-size: 0.9em;
}
#invoice-POS h3 {
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
#invoice-POS p {
  font-size: 0.7em;
  color: #666;
  line-height: 1.2em;
}
#invoice-POS #top,
#invoice-POS #mid,
#invoice-POS #bot {
  /* Targets all id with 'col-' */
  border-bottom: 1px solid #eee;
}
#invoice-POS #top {
  min-height: 100px;
}
#invoice-POS #mid {
  min-height: 80px;
}
#invoice-POS #bot {
  min-height: 50px;
}
#invoice-POS #top .logo {
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
  background-size: 60px 60px;
}
#invoice-POS .clientlogo {
  float: left;
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
  background-size: 60px 60px;
  border-radius: 50px;
}
#invoice-POS .info {
  display: block;
  margin-left: 0;
}
#invoice-POS .title {
  float: right;
}
#invoice-POS .title p {
  text-align: right;
}
#invoice-POS table {
  width: 100%;
  border-collapse: collapse;
}
#invoice-POS .tabletitle {
  font-size: 0.5em;
  background: #eee;
}
#invoice-POS .service {
  border-bottom: 1px solid #eee;
}
#invoice-POS .item {
  width: 24mm;
}
#invoice-POS .itemtext {
  font-size: 0.5em;
}
#invoice-POS #legalcopy {
  margin-top: 5mm;
}
</style> -->

