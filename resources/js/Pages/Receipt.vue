<script setup>
import { usePage  } from "@inertiajs/inertia-vue3";
import { watch, toRefs, computed, ref } from "vue";
import moment from 'moment'

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

      <table cellspacing="0" cellpadding="0" class="mt-5">
        <thead>
          <tr>
            <th class="description">Name</th>
            <th class="quantity">Items</th>
            <th class="price">Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, index) in selectedProducts.slice(0, itemNumber)" :key="index">
            <td  style="font-size: 9.5pt;" class="description">{{ product.selectedproductName}}</td>
            <td  class="quantity">{{ product.productQuantity}} </td>
            <td  class="price">KES. {{ product.productPrice }}</td>
          </tr>
        </tbody>
      </table>

      <!-- <div style="display: grid; 
margin-top: 1.25rem;
margin-bottom: 1.25rem; 
width: 24rem; 
grid-template-columns: repeat(4, minmax(0, 1fr)); 
gap: 1rem; ">
        <div>Item</div>
        <div>Quantity</div>
        <div>Price</div>
        <div>Total</div>
      </div> -->

      <!-- <div class="flex flex-row justify-between">
        <div class="flex flex-cols justify-between">
          <p>Item</p>
          <p>Item</p>
          <p>Item</p>
        </div>
        <div class="flex flex-cols justify-between">
          <p>Items</p>
          <p>Items</p>
          <p>Items</p>
        </div>
      </div> -->

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
            Subtotal: KES. {{ overallSubtotal }} 
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
            Tax (16%): KES. {{ overallTax }} 
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
            Grand Total: KES. {{ overallTotal }} 
          </p>


      <div style="font-size: 15.5pt;" class="mt-10">
        <p>Served by {{ salesPerson }}</p>
      </div>
      <div style="font-size: 20.5pt;" class="">
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
  border: none !important;
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
