<template>
    <div class="container pt-2">
        <div class="d-flex bd-highlight">
            <div class="fw-bold mt-3 mb-2">
                <h5>
                    Buku Kas
                </h5>
            </div> 
        </div>
        <vue-good-table 
            :columns="columns"
            :rows="rows"

            :search-options="{
                enabled: true, //untuk menghidupkan fitur pencarian
            }"
            :pagination-options="{
                enabled: true,  //untuk menghidupkan paginasi
                mode: 'records',
            }"
            :line-numbers="true">
            <template v-slot:table-row="props">
                <span v-if="props.column.label == 'Actions'">
                    <div class="d-flex">
                        <router-link class="btn btn-primary btn-sm me-2 w-50" :to="{name: 'AdminProdukEdit', params: {id: props.row.id}}"><i class="fa-regular fa-pen-to-square"></i> Edit<br>
                        </router-link>
                        <a class="btn btn-danger btn-sm w-50"><i class="fa-regular fa-trash-can"></i> Hapus</a>     
                    </div>                   
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </template>
        </vue-good-table>       
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data(){
        return {
            columns: [
                {
                    label: "Pembeli", //label akan muncul sebagai header kolom
                    field: "transaksi.pembeli.name", //sesuaikan nama field dengan nama row
                    tdClass: 'align-middle pl-2',
                    thClass: 'text-center',
                },
                {
                    label: "Produk",
                    field: "transaksi.produk.nama_produk",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                },
                {
                    label: "Kuantitas",
                    field: "transaksi.jumlah_produk",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                },
                {
                    label: "Total Transaksi",
                    field: "transaksi.total_transaksi",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                },
                {
                    label: "Saldo Awal",
                    field: "saldo_awal",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                },
                {
                    label: "Saldo Akhir",
                    field: "saldo_akhir",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                }
            ],
            rows: [],
        }
    },
    methods: {
        getProduk(){
            axios.get('/bukukas')
                .then((response) => {
                    console.log(response);
                    this.rows = response.data.data;
                })
        },
    },
    beforeMount() {
        this.getProduk();
    }
}
</script>