<script setup>
// import { Head, Link } from "@inertiajs/inertia-vue3";
// import { ref } from "vue";
// import { Inertia } from "@inertiajs/inertia";

const printPDF = () =>
{ 
    // const $btnPrint = document.querySelector("#btnPrint");
    // $btnPrint.addEventListener("click", () => {
    //   });
  window.print();
}

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
<div>

    <div>
        <div id="htmlContent" class="ticket">
            <!-- <img src="./logo.png" alt="Logo"> -->
            <p class="centered">RECEIPT EXAMPLE
                <br>Address line 1
                <br>Address line 2</p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Q.</th>
                        <th class="description">Description</th>
                        <th class="price">$$</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="quantity">1.00</td>
                        <td class="description">ARDUINO UNO R3</td>
                        <td class="price">$25.00</td>
                    </tr>
                    <tr>
                        <td class="quantity">2.00</td>
                        <td class="description">JAVASCRIPT BOOK</td>
                        <td class="price">$10.00</td>
                    </tr>
                    <tr>
                        <td class="quantity">1.00</td>
                        <td class="description">STICKER PACK</td>
                        <td class="price">$10.00</td>
                    </tr>
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">TOTAL</td>
                        <td class="price">$55.00</td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">Thanks for your purchase!
                <br>parzibyte.me/blog</p>
        </div>
        <button @click="printPDF" id="btnPrint" class="hidden-print">Print</button>
        <!-- <script src="script.js"></script> -->
    </div>

    <button @click="generatePDF" clas="mt-10"> Download PDF </button>
</div>

</template>

<style scoped>
* {
    font-size: 12px;
    font-family: 'Times New Roman';
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
}

td.description,
th.description {
    width: 75px;
    max-width: 75px;
}

td.quantity,
th.quantity {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

td.price,
th.price {
    width: 40px;
    max-width: 40px;
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

img {
    max-width: inherit;
    width: inherit;
}

@media print {
    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}
</style>
