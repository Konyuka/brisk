<script setup>
// import { Head, Link } from "@inertiajs/inertia-vue3";
import { watch, toRefs, computed, ref } from "vue";
import moment from 'moment'

const props = defineProps({
    testProp: Boolean,
});

const { testProp } = toRefs(props)

const invoiceNumber = ref(JSON.parse(localStorage.getItem('invoiceNumber')))
const productName = ref(JSON.parse(localStorage.getItem('productName')))
const saleQuantity = ref(JSON.parse(localStorage.getItem('saleQuantity')))
const salePrice = ref(JSON.parse(localStorage.getItem('salePrice')))
const subTotal = ref(JSON.parse(localStorage.getItem('subTotal')))
const tax = ref(JSON.parse(localStorage.getItem('tax')))
const grandTotal = ref(JSON.parse(localStorage.getItem('grandTotal')))

const invoiceTime = computed(() => {
    return moment().format('MMMM Do YYYY, h:mm:ss a');
});

watch(testProp, (value) => {
  generatePDF();
  // console.log(value)
})

// const printPDF = () => {
//   window.print();
// };

const generatePDF = () =>
{
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
  doc.save(invoiceNumber.value+".pdf");
};

</script>

<template>
  <div>
    <div id="htmlContent" class="">
      <div class="">
        <h1
          style="
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 35.5pt;
            margin: 0pt;
          "
        >
          Brisk International <br />
          <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: normal;
              text-decoration: none;
              font-size: 19.5pt;
              margin: 0pt;
            "
          >
            36346-00200 NRB,KE
          </p>
          <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: normal;
              text-decoration: none;
              font-size: 19.5pt;
              margin: 0pt;
            "
          >
            PIN P051364977Q
          </p>
          <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: normal;
              text-decoration: none;
              font-size: 25.5pt;
              margin-top: 15pt;
            "
          >
            Invoice #{{ invoiceNumber }}
            <p
              style="
                color: black;
                font-family: Arial, sans-serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 18.5pt;
                margin: 0pt;
              "
            >
              {{ invoiceTime }}
            </p>
          </p>
        </h1>
      </div>

      <table class="mt-5">
        <thead>
          <tr>
            <th class="description">Name</th>
            <th class="quantity">Items</th>
            <th class="price">Price</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-size: 9.5pt;" class="description">{{ productName}}</td>
            <td class="quantity">{{ saleQuantity}} Item(s)</td>
            <td class="price">KES. {{ salePrice }}</td>
          </tr>
        </tbody>
      </table>
        <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: bold;
              text-decoration: none;
              font-size: 20.5pt;
              margin: 0pt;
            "
          >
            Subtotal: KES. {{ subTotal }} 
          </p>
        <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: bold;
              text-decoration: none;
              font-size: 20.5pt;
              margin: 0pt;
            "
          >
            Tax (16%): KES. {{ tax}} 
          </p>
        <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: bold;
              text-decoration: none;
              font-size: 20.5pt;
              margin: 0pt;
            "
          >
            Grand Total: KES. {{ grandTotal }} 
          </p>


      <div style="font-size: 20.5pt;" class="mt-10">
        <p>Thank you for the purchase</p>
      </div>
    </div>

    <button @click="generatePDF" clas="mt-10">Download PDF</button> <br>
    <!-- <button @click="printPDF" clas="mt-10">Print PDF</button> -->
  </div>
</template>

<style scoped>
td,
th,
tr,
table {
  border-top: 0.3px solid white;
  border-collapse: collapse;
}

td.description,
th.description {
  width: 95px;
  max-width: 95px;
}

td.quantity,
th.quantity {
  width: 70px;
  max-width: 70px;
  word-break: break-all;
}


td.price,
th.price {
  width: 50px;
  max-width: 50px;
  word-break: break-all;
}

.centered {
  text-align: center;
  align-content: center;
}

.ticket {
  width: 155px;
  max-width: 155px;
}

@media print {
  .hidden-print,
  .hidden-print * {
    display: none !important;
  }
}
</style>
