<template>
    <div class="container">
        <h1>{{name}}</h1>
        <div class="form-group">
            <input type="text" @blur="saveName" v-model="name" class="form-control" :class="{ 'is-invalid': $v.name.$error }">

            <div class="invalid-feedback" v-if="!$v.name.required">
                Обязательное поле
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength">
                Максимальное количевство символов: {{$v.name.$params.maxLength.max}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4" v-for="desk_list in desk_lists">
                <div class="card mt-3">
                    <a href="" class="card-body">
                        <h4 class="card-title">{{desk_list.name}}</h4>
                    </a>
                </div>
            </div>
        </div>
        <div class="alert alert-danger" role="alert"  v-if="errored">
            Error get
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
            errored: false,
            loading: true,
            desk_lists: true
        }
    },
    methods:{
        getDeskList(){

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
        }
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
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255)
        }
    }
}
</script>
