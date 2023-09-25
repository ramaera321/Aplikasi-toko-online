<template>
    <div class="container pt-2">
        <div class="d-flex bd-highlight">
            <div class="fw-bold mt-3 mb-2">
                <h5>
                    Kategori
                </h5>
            </div> 
            <div class="fw-bold mt-3 mb-2 ms-auto">
                <button class="btn btn-success btn" @click="showModal(null)"><i class="fa-regular fa-pen-to-square"></i> Create</button>
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
                        <button class="btn btn-primary btn-sm w-50" @click="showModal(props.row)"><i class="fa-regular fa-pen-to-square"></i> Update</button>
                        <a class="btn btn-danger btn-sm w-50" @click="deleteKategori(props.row.id)"><i class="fa-regular fa-trash-can"></i> Hapus</a>     
                    </div>                   
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </template>
        </vue-good-table> 

        <div class="modal" :class="{'d-none':!isModalVisible}" id="modalForm" style="display: block">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Create Kategori</h5>
                        <button type="button" class="btn-close" aria-label="Close" @click="showModal(null)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Nama Kategori</label>
                            <input v-model="nama_kategori" type="text" class="form-control" id="kategori">
                            <input v-model="id" type="hidden" class="form-control" id="id_kategori">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="showModal(null)">Close</button>
                        <button type="button" class="btn btn-primary" @click="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
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
                    label: "Kategori", //label akan muncul sebagai header kolom
                    field: "nama_kategori", //sesuaikan nama field dengan nama row
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
            nama_kategori: null,
            id: null,
            url: null,
            message: null,
            isModalVisible: false,
        }
    },
    methods: {
        showModal(data = null){
            if(data != null){
                const kategoriData = JSON.parse(JSON.stringify(data));
                this.id = kategoriData.id;
                this.nama_kategori = kategoriData.nama_kategori;
            }
            this.isModalVisible = !this.isModalVisible;
        },
        getKategori(){
            axios.get('/kategori')
                .then((response) => {
                    this.rows = response.data.data;
                })
        },
        async submit(){
            const formData = new FormData;
            formData.append('nama_kategori', this.nama_kategori);

            if(this.id == null) {
                this.url = '/kategori/store';
                this.message = 'menambahkan';
            } else {
                this.url = `/kategori/${this.id}/update`;
                this.message = 'mengubah';
            }

            await axios.post(this.url, formData)
                .then((response) => {
                     if(response.status == 200){
                            Swal.fire({
                                title: 'Berhasil !!',
                                text: `Berhasil ${this.message} kategori`,
                                icon: 'success',
                            })
                            this.isModalVisible = !this.isModalVisible;
                            this.getKategori();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `Gagal ${this.message} kategori`,
                            })
                        }
                })
                .catch((err) => {
                    console.log(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Gagal ${this.message} kategori`,
                    })
                })
        },
        async deleteKategori(id){
            await axios.delete(`/kategori/${id}/delete`)
                .then((response) => {
                    if(response.status == 200){
                        Swal.fire({
                            title: 'Berhasil !!',
                            text: `Berhasil menghapus kategori`,
                            icon: 'success',
                        })
                        this.getKategori();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Gagal menghapus kategori`,
                        })
                    }
                })
                .catch((error) =>{
                    console.log(error);
                    Swal.fire({
                        title: 'Gagal !!',
                        text: `Gagal menghapus kategori`,
                        icon: 'error',
                    })
                })
        }
    },
    beforeMount() {
        this.getKategori();
    }
}
</script>