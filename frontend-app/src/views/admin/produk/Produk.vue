<template>
    <div class="container pt-2">
        <div class="d-flex bd-highlight">
            <div class="fw-bold mt-3 mb-2">
                <h5>
                    Produk
                </h5>
            </div> 
            <div class="fw-bold mt-3 mb-2 ms-auto">
                <router-link class="btn btn-success btn" :to="{name: 'AdminProdukCreate'}"><i class="fa-regular fa-pen-to-square"></i> Create<br>
                </router-link>
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
                        <a class="btn btn-danger btn-sm w-50" @click="deleteProduk(props.row.id, props.row.nama_produk)"><i class="fa-regular fa-trash-can"></i> Hapus</a>     
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
import Swal from 'sweetalert2'

export default {
    data(){
        return {
            columns: [
                {
                    label: "Nama Produk", //label akan muncul sebagai header kolom
                    field: "nama_produk", //sesuaikan nama field dengan nama row
                    tdClass: 'align-middle pl-2',
                    thClass: 'text-center',
                },
                {
                    label: "Kategori",
                    field: "kategori.nama_kategori",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                },
                {
                    label: "Harga",
                    field: "harga",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                },
                {
                    label: "Stok",
                    field: "stok",
                    tdClass: 'align-middle text-center',
                    thClass: 'text-center',
                },
                {
                    label: 'Actions',
                    field: 'actions',
                    tdClass: 'align-middle',
                    thClass: 'text-center',
                    sortable: false,
                }
            ],
            rows: [],
        }
    },
    methods: {
        getProduk(){
            axios.get('/produk')
                .then((response) => {
                    this.rows = response.data.data;
                })
        },
        deleteProduk(id, nama){
            Swal.fire({
                title: 'Peringatan !!',
                text: `Apakah anda yakin ingin menghapus produk ${nama} ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oke',
            }).then((result) => {
                if(result.isConfirmed){
                    axios.delete(`/produk/${id}/delete`)
                    .then((response) => {
                        if(response.status == 200){
                            Swal.fire({
                                title: 'Berhasil !!',
                                text: 'Berhasil menghapus produk',
                                icon: 'success',
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal menghapus produk',
                            })
                        }
                    })
                    .catch((err) => {
                        console.log(err)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal menghapus produk',
                        })
                    })
                }
            })
        },
    },
    beforeMount() {
        this.getProduk();
    }
}
</script>