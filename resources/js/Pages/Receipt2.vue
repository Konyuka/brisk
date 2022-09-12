<script setup>
// import { Head, Link } from "@inertiajs/inertia-vue3";
import { watch, toRefs, computed, ref } from "vue";
import moment from "moment";

const props = defineProps({
  selectedProducts: Array,
});

const { selectedProducts } = toRefs(props);

const invoiceNumber = ref(JSON.parse(localStorage.getItem("invoiceNumber")));
const productName = ref(JSON.parse(localStorage.getItem("productName")));
const saleQuantity = ref(JSON.parse(localStorage.getItem("saleQuantity")));
const salePrice = ref(JSON.parse(localStorage.getItem("salePrice")));
const subTotal = ref(JSON.parse(localStorage.getItem("subTotal")));
const tax = ref(JSON.parse(localStorage.getItem("tax")));
const grandTotal = ref(JSON.parse(localStorage.getItem("grandTotal")));

const invoiceTime = computed(() => {
  return moment().format("MMMM Do YYYY, h:mm:ss a");
});

watch(selectedProducts, (value) => {
  // generatePDF();
  console.log(value);
});

// const printPDF = () => {
//   window.print();
// };

const generatePDF = () => {
  var doc = new jsPDF();
  var specialElementHandlers = {
    "#editor": function (element, renderer) {
      return true;
    },
  };

  doc.setFontSize(24);
  doc.fromHTML($("#htmlContent").html(), 15, 15, {
    width: 170,
    // 'height': "44mm",
    elementHandlers: specialElementHandlers,
  });
  doc.save(invoiceNumber.value + ".pdf");
};
</script>

<template>
  <div id="htmlContent">
    
    
  <div  id="invoice-POS">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
        <h2>SBISTechs Inc</h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
        <h2>Contact Info</h2>
        <p> 
            Address : street city, state 0000<br>
            Email   : JohnDoe@gmail.com<br>
            Phone   : 555-555-5555<br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    
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

							<tr class="service">
								<td class="tableitem"><p class="itemtext">Asset Gathering</p></td>
								<td class="tableitem"><p class="itemtext">3</p></td>
								<td class="tableitem"><p class="itemtext">$225.00</p></td>
							</tr>

							<tr class="service">
								<td class="tableitem"><p class="itemtext">Design Development</p></td>
								<td class="tableitem"><p class="itemtext">5</p></td>
								<td class="tableitem"><p class="itemtext">$375.00</p></td>
							</tr>

							<tr class="service">
								<td class="tableitem"><p class="itemtext">Animation</p></td>
								<td class="tableitem"><p class="itemtext">20</p></td>
								<td class="tableitem"><p class="itemtext">$1500.00</p></td>
							</tr>

							<tr class="service">
								<td class="tableitem"><p class="itemtext">Animation Revisions</p></td>
								<td class="tableitem"><p class="itemtext">10</p></td>
								<td class="tableitem"><p class="itemtext">$750.00</p></td>
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
					</div><!--End Table-->

					<div id="legalcopy">
						<p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days; please process this invoice within that time. There will be a 5% interest charge per month on late invoices. 
						</p>
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->


    <button @click="generatePDF" clas="mt-10">Download PDF</button> <br />
    <!-- <button @click="printPDF" clas="mt-10">Print PDF</button> -->
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400|Work+Sans:300,500");
body {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  color: #525252;
  font-family: "Work Sans", sans-serif;
  font-weight: 300;
  line-height: 1.25;
  background: linear-gradient(to bottom, #00b09b, #96c93d);
}

a {
  color: #5c7b25;
  -webkit-text-decoration-skip: ink;
          text-decoration-skip: ink;
}

.receipt {
  position: relative;
  flex: none;
  padding: 30px 0;
  background: #fff;
  -webkit-clip-path: polygon(100% 0, 100% calc(100% - 6px), 98% 100%, 96% calc(100% - 6px), 94% 100%, 92% calc(100% - 6px), 90% 100%, 88% calc(100% - 6px), 86% 100%, 84% calc(100% - 6px), 82% 100%, 80% calc(100% - 6px), 78% 100%, 76% calc(100% - 6px), 74% 100%, 72% calc(100% - 6px), 70% 100%, 68% calc(100% - 6px), 66% 100%, 64% calc(100% - 6px), 62% 100%, 60% calc(100% - 6px), 58% 100%, 56% calc(100% - 6px), 54% 100%, 52% calc(100% - 6px), 50% 100%, 48% calc(100% - 6px), 46% 100%, 44% calc(100% - 6px), 42% 100%, 40% calc(100% - 6px), 38% 100%, 36% calc(100% - 6px), 34% 100%, 32% calc(100% - 6px), 30% 100%, 28% calc(100% - 6px), 26% 100%, 24% calc(100% - 6px), 22% 100%, 20% calc(100% - 6px), 18% 100%, 16% calc(100% - 6px), 14% 100%, 12% calc(100% - 6px), 10% 100%, 8% calc(100% - 6px), 6% 100%, 4% calc(100% - 6px), 2% 100%, 0 calc(100% - 6px), 0 0);
          clip-path: polygon(100% 0, 100% calc(100% - 6px), 98% 100%, 96% calc(100% - 6px), 94% 100%, 92% calc(100% - 6px), 90% 100%, 88% calc(100% - 6px), 86% 100%, 84% calc(100% - 6px), 82% 100%, 80% calc(100% - 6px), 78% 100%, 76% calc(100% - 6px), 74% 100%, 72% calc(100% - 6px), 70% 100%, 68% calc(100% - 6px), 66% 100%, 64% calc(100% - 6px), 62% 100%, 60% calc(100% - 6px), 58% 100%, 56% calc(100% - 6px), 54% 100%, 52% calc(100% - 6px), 50% 100%, 48% calc(100% - 6px), 46% 100%, 44% calc(100% - 6px), 42% 100%, 40% calc(100% - 6px), 38% 100%, 36% calc(100% - 6px), 34% 100%, 32% calc(100% - 6px), 30% 100%, 28% calc(100% - 6px), 26% 100%, 24% calc(100% - 6px), 22% 100%, 20% calc(100% - 6px), 18% 100%, 16% calc(100% - 6px), 14% 100%, 12% calc(100% - 6px), 10% 100%, 8% calc(100% - 6px), 6% 100%, 4% calc(100% - 6px), 2% 100%, 0 calc(100% - 6px), 0 0);
}
.receipt__title {
  margin-bottom: 15px;
  padding: 0 30px;
  font-family: "Work Sans", sans-serif;
  font-weight: 500;
  font-size: 40px;
  color: #00b09b;
}
.receipt__subtitle {
  padding: 0 30px;
  font-family: "Work Sans", sans-serif;
  font-weight: 300;
  font-size: 18px;
}
.receipt__lines {
  margin-top: 50px;
  padding: 30px;
  border-top: 1px dashed #dce2d6;
}
.receipt__line {
  display: flex;
  margin: 20px 0;
  justify-content: space-between;
  font-family: "Roboto Condensed", sans-serif;
  font-size: 18px;
}
.receipt__line__item {
  font-weight: 300;
}
.receipt__line__price {
  font-weight: 400;
}
.receipt__total {
  display: flex;
  margin: 20px 0;
  padding: 30px;
  justify-content: space-between;
  font-family: "Roboto Condensed", sans-serif;
  font-size: 24px;
  background-color: #eff7e8;
}
.receipt__total__item, .receipt__total__price {
  font-weight: 400;
}
.receipt__back {
  text-align: center;
}
</style>
