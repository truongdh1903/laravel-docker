<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Register</h1>
                        <hr/>
                        <form action="javascript:void(0)" @submit="register" class="row" method="post">
                            <div class="col-12" v-if="Object.keys(validationErrors).length > 0">
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="name" class="font-weight-bold">Name</label>
                                <input type="text" name="name" v-model="user.name" id="name" placeholder="Enter name" class="form-control" required>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input type="text" name="email" v-model="user.email" id="email" placeholder="Enter email" class="form-control" required>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="phone" class="font-weight-bold">Phone</label>
                                <input type="text" name="phone" v-model="user.phone" id="phone" placeholder="Enter phone" class="form-control" required>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="address" class="font-weight-bold">Address</label>
                                <input type="text" name="address" v-model="user.address" id="address" placeholder="Enter address" class="form-control" required>
                            </div>
                            <div class="form-group col-12">
                                <label for="password" class="font-weight-bold">Password</label>
                                <input type="password" name="password" v-model="user.password" id="password" placeholder="Enter Password" class="form-control" required>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password_confirmation" class="font-weight-bold">Confirm Password</label>
                                <input type="password_confirmation" name="password_confirmation" v-model="user.password_confirmation" id="password_confirmation" placeholder="Enter Password" class="form-control" required>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="type" class="font-weight-bold">Type</label>
                                <select id="type" name="type" v-model="user.type" class="form-control" required>
                                    <option value="1">Shop</option>
                                    <option value="2">Shipper</option>
                                    <option value="3">Buyer</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Register" }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <label>Already have an account? <router-link :to="{name:'login'}">Login Now!</router-link></label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    name: 'register',
    data(){
        return {
            user:{
                name:"",
                email:"",
                phone:"",
                address:"",
                password:"",
                password_confirmation:"",
                type: 1,
            },
            validationErrors:{},
            processing:false
        }
    },
    methods:{
        ...mapActions({
            signIn:'auth/login'
        }),
        async register(){
            this.processing = true
            await window.myAxios.post('register', this.user).then(res => {
                this.validationErrors = {}
                window.localStorage.setItem('token', res.data.access_token)
                this.signIn(res.data.access_token)
            }).catch(({response})=>{
                if(response.status===422){
                    this.validationErrors = response.data.errors
                }else{
                    this.validationErrors = {}
                    alert(response.data.message)
                }
            }).finally(()=>{
                this.processing = false
            })
        }
    }
}
</script>