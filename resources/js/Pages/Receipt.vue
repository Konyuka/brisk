<script setup>
import { usePage  } from "@inertiajs/inertia-vue3";
import { watch, toRefs, computed, ref } from "vue";
import moment from 'moment'
// import jsPDF from "jspdf";
import autoTable from 'jspdf-autotable';

const props = defineProps({
  selectedProducts: Array,
  invoiceLog: String,
  overallSubtotal: String,
  overallTax: String,
  overallTotal: String,
  printTrigger: String,
});

const { selectedProducts } = toRefs(props)
const { invoiceLog } = toRefs(props)
const { overallSubtotal } = toRefs(props)
const { overallTax } = toRefs(props)
const { overallTotal } = toRefs(props)
const { printTrigger } = toRefs(props)

const salesPerson = computed(() => usePage().props.value.user.name)

const itemNumber = computed(() => { return selectedProducts.value.length - 1  } )

const invoiceTime = computed(() => {
    return moment().format('MMMM Do YYYY, h:mm:ss a');
});

watch(printTrigger, (value) => {
  generatePDF();
  // console.log(value)
})

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
    elementHandlers: specialElementHandlers,
  });
  // autoTable(doc, {
  //   html: '#my-table',
  //   theme: 'plain',
  // });
  doc.save(invoiceLog.value+".pdf");
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
          Brisk International LTD.<br />
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
            Invoice # {{ invoiceLog }}
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
            <th class="quantity">SKU</th>
            <th class="quantity">Items</th>
            <th class="price">Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, index) in selectedProducts.slice(0, itemNumber)" :key="index">
            <td  style="font-size: 9.5pt;" class="description">{{ product.selectedproductName}}</td>
            <td  class="quantity">{{ product.productSKU }} </td>
            <td  class="quantity">{{ product.productQuantity}} </td>
            <td  class="price">KES. {{ product.salePrice }}</td>
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
              font-size: 18.5pt;
              margin: 0pt;
            "
          >
            <!-- Subtotal: KES. {{ overallSubtotal }}  -->
          </p>
        <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: bold;
              text-decoration: none;
              font-size: 18.5pt;
              margin: 0pt;
            "
          >
            VAT (16%): KES. {{ overallTax }} 
          </p>
        <p
            style="
              color: black;
              font-family: Arial, sans-serif;
              font-style: normal;
              font-weight: bold;
              text-decoration: none;
              font-size: 18.5pt;
              margin: 0pt;
            "
          >
            Grand Total: KES. {{ overallTotal }} 
          </p>


      <div style="font-size: 18.5pt;" class="">
        <p>Served by {{ salesPerson }}</p>
      </div>
      <div style="font-size: 18.5pt;" class="">
        <p>Thank you for the purchase</p>
      </div>
    </div>

    <button @click="generatePDF" clas="mt-10">Download PDF</button> <br>
  </div>
</template>

<!-- border-collapse: collapse !important;
  border-spacing: 0px !important;
  border-style: outset !important; -->
<style scoped>

  
td,
th,
tr,
table {
  border: none !important;
  border: hidden !important;
  border-style: hidden !important;
  border-style: none !important;
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

@media print {
  .hidden-print,
  .hidden-print * {
    display: none !important;
  }
}
</style>
