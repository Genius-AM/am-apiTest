<template>
    <div class="container">
        <h1>{{name}}</h1>
        <div class="form-group">
            <label class="col-form-label">Изменить название доски</label>
            <input type="text" @blur="saveName" v-model="name" class="form-control" :class="{ 'is-invalid': $v.name.$error }">

            <div class="invalid-feedback" v-if="!$v.name.required">
                Обязательное поле
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength">
                Максимальное количевство символов: {{$v.name.$params.maxLength.max}}
            </div>
        </div>
        <br>
        <form @submit.prevent="addNewDeskList">

            <div class="form-group">
                <label class="col-form-label">Добавление списка</label>
                <input type="text" v-model="desk_list_name" @blur="addNewDeskList" placeholder="Введите название списка" class="form-control" :class="{ 'is-invalid': $v.desk_list_name.$error }">
                <div class="invalid-feedback" v-if="!$v.desk_list_name.required">
                    Обязательное поле
                </div>

                <div class="invalid-feedback" v-if="!$v.desk_list_name.maxLength">
                    Максимальное количевство символов: {{$v.desk_list_name.$params.maxLength.max}}
                </div>

                <div class="alert alert-danger" role="alert" v-if="errored">
                    Ошибка <br>
                    {{errors[0]}}
                </div>
            </div>
            <button type="button" class="btn btn-primary mb-3">Создать список</button>

        </form>

        <div class="row">
            <div class="col-lg-4" v-for="desk_list in desk_lists">
                <div class="card mt-3">
                    <div class="card-body">
                        <form @submit.prevent="updateDeskList(desk_list.id, desk_list.name)" v-if="desk_list_input_id == desk_list.id" class="d-flex justify-content-between align-items-center">
                            <input type="text" v-model="desk_list.name" class="form-control" placeholder="Введите название списка">
                            <button type="button" @click="desk_list_input_id = null" class="btn-close" aria-label="Close"></button>
                        </form>
                        <h4 v-else class="card-title d-flex justify-content-between align-items-center" style="color: rgb(90, 90, 90)" @click="desk_list_input_id = desk_list.id">{{desk_list.name}} <i class="fa-solid fa-pencil" style="cursor: pointer; font-size: 15px;"></i></h4>
                    </div>
                    <button type="submit" class="btn btn-danger mt-3" @click="deleteDeskList(desk_list.id)">Удалить</button>
                </div>
            </div>
        </div>

        <div class="alert alert-danger" role="alert"  v-if="errored">
            Ошибка загрузки данных!
        </div>

        <div class="spinner-grow" style="width: 5rem; height: 5rem;" role="status" v-if="loading">
            <span class="visually-hidden">Loading...</span>
        </div>

    </div>

</template>

<script>

import { required, maxLength } from 'vuelidate/lib/validators'

export default {
    props: [
        'deskId'
    ],
    data(){
        return{
            name: null,
            desk_list_name: null,
            errored: false,
            loading: true,
            desk_lists: true,
            desk_list_input_id: null,
        }
    },
    methods:{
        updateDeskList(id, name){
            axios.post('/api/desk-lists/' + id, {
                _method: 'PUT',
                name: name
            })
                .then(response => {
                    this.desk_list_input_id = null
                    this.getDeskList()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() =>{
                    this.loading = false
                })
        },
        getDeskList(){
            axios.get('/api/desk-lists/' , {
               params: {
                   desk_id: this.deskId
               }
            })
                .then(response => {
                    this.desk_lists = response.data.data
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() =>{
                    this.loading = false
                })
        },
        saveName(){
            this.$v.$touch()
            if (this.$v.$anyError){
                return
            }
            axios.post('/api/desks/' + this.deskId, {
                _method: 'PUT',
                name: this.name,
            })
                .then(response => {

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() =>{
                    this.loading = false
                })
        },
        addNewDeskList(){
            this.$v.$touch()
            if (this.$v.$anyError){
                return;
            }
            axios.post('/api/desk-lists' , {
                name: this.desk_list_name,
                desk_id: this.deskId,

            })
                .then(response => {
                    this.desk_list_name = ''
                    this.desk_lists = []
                    this.getDeskList()
                })
                .catch(error => {
                    console.log(error)
                    if(this.errors.push(error.response.data.errors.name)){
                        this.errors = []
                        this.errors.push(error.response.data.errors.name[0])
                    }
                    this.errored = true
                })
                .finally(() =>{
                    this.loading = false
                })
        },
        deleteDeskList(id){
            if(confirm('Вы хотите удалить desk?')){
                axios.post('/api/desk-lists/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.desk_lists = []
                        this.getDeskList()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.errored = true
                    })
                    .finally(() =>{
                        this.loading = false
                    })
            }
        },
    },
    mounted() {
        axios.get('/api/desks/' + this.deskId)
            .then(response => {
                this.name = response.data.data.name
            })
            .catch(error => {
                console.log(error)
                this.errored = true
            })
            .finally(() =>{
                this.loading = false
            })
        this.getDeskList()
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255)
        },
        desk_list_name: {
            required,
            maxLength: maxLength(255)
        }
    }
}
</script>
