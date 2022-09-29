<template>
    <toast :message="message"/>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>User management</h3>
                    </div>
                    <div class="card-body">
                        <table id="users">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="user in users" :id="'user-' + user.id">
                                <td>{{user.id}}</td>
                                <td>{{user.name}}</td>
                                <td>{{user.phone}}</td>
                                <td>{{user.address}}</td>
                                <td>{{user.email}}</td>
                                <td>{{roles[user.type]}}</td>
                                <td>
                                    <button @click="$router.push(`/user/${user.id}`)" class="btn btn-outline-primary">Show</button>
                                     
                                    <button @click="deleteUser(user.id)" class="btn btn-outline-danger">Delete</button>
                                </td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Toast from './Toast.vue'
import axios from 'axios'
export default {
    name: "dashboard",
    components: {
        Toast,
    },
    data(){
        return {
            users: [],
            user: this.$store.state.auth.user,
            roles: ['Admin', 'Shop', 'Shipper', 'Buyer'],
            links: [],
            page: 0,
            cur_index: 1,
            message: ''
        }
    },
    created(){
        window.myAxios.get('users', {
            headers: {Authorization: 'Bearer ' + window.localStorage.getItem('token')}
        }).then(res => {
            this.users = res.data.users.data
            this.links = res.data.users.links
            this.page = res.data.users.last_page
        })
    },

    methods: {
        deleteUser(id) {
            window.myAxios.delete(`user/${id}/delete`, {
                headers: {
                    Authorization: 'Bearer ' + window.localStorage.getItem('token')
                }
            }).then(res => {
                $(`#user-${id}`).remove()
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
                this.users = res.data.users.data
            })
        }
    }
}
</script>

<style scoped>
#users {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#users td, #users th {
  border: 1px solid #ddd;
  padding: 8px;
}

#users tr:nth-child(even){background-color: #f2f2f2;}

#users tr:hover {background-color: #ddd;}

#users th {
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