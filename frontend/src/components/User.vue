<template>
    <toast/>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>User info</h3>
                    </div>
                    <div class="card-body">
                        <form action="#">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" placeholder="User name.." v-model="user.name">

                            <label for="phone">Phone</label>
                            <input type="text" id="phone" name="phone" placeholder="User phone.." v-model="user.phone">
                            
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" placeholder="User address.." v-model="user.address">
                            
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" placeholder="User email.." v-model="user.email">

                            <label for="type">User type</label>
                            <select id="type" name="type" v-model="user.type">
                                <option value="1">Shop</option>
                                <option value="2">Shipper</option>
                                <option value="3">Buyer</option>
                            </select>
                        
                            <input type="submit" value="Submit" @click.prevent="editUser()">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" v-if="user.type == 2">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Orders</h3>
                    </div>
                    <div class="card-body">
                        <table id="orders" v-if="orders.length">
                            <tr>
                                <th>Id</th>
                                <th>Customer name</th>
                                <th>Customer address</th>
                                <th>Customer phone</th>
                                <th>Order time</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Income</th>
                            </tr>
                            <tr v-for="order in orders">
                                <td>{{order.id}}</td>
                                <td>{{order.name}}</td>
                                <td>{{order.address}}</td>
                                <td>{{order.phone}}</td>
                                <td>{{order.order_day}}</td>
                                <td>{{statuses[order.status]}}</td>
                                <td>{{order.price}}</td>
                                <td>{{order.deliver_cost}}</td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td colspan="2"><h5>Total earning: {{total}}</h5></td>
                            </tr>
                        </table>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" @click.prevent="getPage(cur_index - 1)" :class="cur_index == 1 ? 'disabled' : ''">Previous</a>
                                </li>
                                <li v-for="index in page" class="page-item" :key="index">
                                    <a :class="index == cur_index ? 'highlight page-link' : 'page-link'" href="javascript:void(0)" @click.prevent="getPage(index)">{{index}}</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0)" @click.prevent="getPage(cur_index + 1)" :class="cur_index == page ? 'disabled' : ''">Next</a></li>
                            </ul>
                        </nav>
                        <h4 v-if="message.length">{{message}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios'
import Toast from './Toast.vue'
export default {
    name: "user",
    components: {
        Toast
    },
    data(){
        return {
            user: {},
            orders: {},
            message: '',
            total: 0,
            links: [],
            page: 0,
            cur_index: 1,
            statuses: ['Pending', 'Accepted', 'Picked', 'Delivering', 'Delivered']
        }
    },
    methods: {
        editUser(){
            window.myAxios.put(`user/${this.user.id}/edit`, this.user).then(res => {
                $('.toast').show()
                setTimeout(() => {
                    $('.toast').hide()
                }, 2000);
            })
        },

        getPage(index) {
            axios.get(this.links[index].url, {
                headers: {
                    Authorization: 'Bearer ' + window.localStorage.getItem('token')
                }
            }).then(res => {
                this.cur_index = index
                this.orders = res.data.orders.data
            })
        }   
    },
    created() {
        window.myAxios.get(`user/${this.$route.params.id}`, {
            headers: {Authorization: 'Bearer ' + window.localStorage.getItem('token')}
        }).then(res => {
            this.user = res.data.user

            if(this.user.type == 2) {
                window.myAxios.get(`order`, {
                    params: {
                        shipper_id: this.user.id
                    },
                    headers: {
                        Authorization: 'Bearer ' + window.localStorage.getItem('token')
                    }
                }).then(res => {
                if(res.data.orders) {
                    this.orders = res.data.orders.data
                    this.total = res.data.total
                    this.page = res.data.orders.last_page
                    this.links = res.data.orders.links
                } else {
                    this.message = res.data.message
                }
            })
        }
        })
    }
}
</script>

<style scoped>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}

#orders {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#orders td, #orders th {
  border: 1px solid #ddd;
  padding: 8px;
}

#orders tr:nth-child(even){background-color: #f2f2f2;}

#orders tr:hover {background-color: #ddd;}

#orders th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

.disabled {
    pointer-events: none;
}

.highlight {
    background: #9ad6fb;
    border: 1px solid # 72ade4;
    color: #4879a6;
    background: linear-gradient(top, # 9ad6fb 0 % ,
    #77c4fc 100%);
    background: -webkit-linear-gradient(top, # 9ad6fb 0 % ,
    #77c4fc 100%);
    box-shadow: inset 0 1px 4px rgba(255,255,255,0.75), 0 1px 3px rgba(79,126,167,.5);
}
</style>