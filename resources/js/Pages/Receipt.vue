<script setup>
// import { Head, Link } from "@inertiajs/inertia-vue3";
// import { ref } from "vue";
// import { Inertia } from "@inertiajs/inertia";

const printPDF = () => {
  // const $btnPrint = document.querySelector("#btnPrint");
  // $btnPrint.addEventListener("click", () => {
  //   });
  window.print();
  
};

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
  doc.save("sample_file.pdf");
};

defineProps({
  //   canLogin: Boolean,
});
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
            Invoice #21
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
              19/08/22 at 20:41AM
            </p>
          </p>
        </h1>
      </div>

      <table class="mt-5">
        <thead>
          <tr>
            <th class="description">Description</th>
            <th class="quantity">#.Items</th>
            <th class="price">Pricing</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="font-size: 9.5pt;" class="description">ARDUINO UNO R3</td>
            <td class="quantity">01</td>
            <td class="price">KES. 25</td>
          </tr>
          <!-- <tr class="mt-5" style="padding-top: 50px;">
            <td class="quantity"></td>
            <td class="description">SUBTOTAL</td>
            <td class="price">KES. 55</td>
          </tr>
          <tr>
            <td class="quantity"></td>
            <td class="description">TAX (16%)</td>
            <td class="price">KES. 55</td>
          </tr>
          <tr>
            <td class="quantity"></td>
            <td class="description">GRAND TOTAL</td>
            <td class="price">KES. 55</td>
          </tr> -->
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
            Subtotal: KES. 55 
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
            Tax (16%): KES. 55 
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
            Grand Total: KES. 110 
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
