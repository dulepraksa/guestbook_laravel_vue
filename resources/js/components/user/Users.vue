<template>
    <div>
       <div class="add-user-div">
           <div v-if="userRole[0].name == 'Administrator' || userRole[0]=='Secretary'" class="add-room-type">
                <button  @click="userTypeModal" class="modal-button" >Manage types</button>
                <button @click="createRoomModal" class="modal-button">Manage Rooms</button>
           </div>
           <div v-else>

           </div>
           <button @click="modal =! modal" class="modal-button gray">Crate new user</button>
        </div>
        <transition name="fade" mode="out-in">
            <div v-if="modal" class="create-meeting-modal">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-header">
                            <h3>Create user</h3>
                        </div>
                        <div class="modal-body">
                            <div v-if="image" class="modal-item" style="align-items:center;">
                                <img :src="image" width="60px" height="60px" style="border-radius:50%;" alt="">
                            </div>
                            <div class="modal-item">
                                <label for="">Name:*</label>                 
                                <input type="text" v-model="name" placeholder="Name:">
                                <span class="errors" v-if="errors.name"><i class="fa fa-warning"></i>Name field is required</span>
                            </div>

                            <div class="modal-item">                            
                                <label for="">Surname:*</label>
                                <input type="text" v-model="surname" placeholder="Surname:">
                                <span class="errors" v-if="errors.surname"><i class="fa fa-warning"></i>Surname field is required</span>
                            </div>

                            <div class="modal-item">                            
                                <label for="">JMBG:*12 numbers</label>
                                <input type="text" v-model="jmbg" placeholder="JMBG:">
                            </div> 

                            <div class="modal-item">                            
                                <label for="">Personal id:*9 numbers</label>
                                <input type="text" v-model="p_id" placeholder="Personal ID:">
                            </div> 
                                
                            <div class="modal-item">
                                <label for="">E-mail:</label>
                                <input type="email" v-model="email" placeholder="E-mail:">
                                <span class="errors" v-if="errors.email"><i class="fa fa-warning"></i>Email field is required</span>
                            </div>

                            <div class="modal-item">
                                <label for="">Password:</label>
                                <input type="password" v-model="password" placeholder="Password:">
                                <span class="errors" v-if="errors.password"><i class="fa fa-warning"></i>Password field is required</span>
                            </div>

                            <div v-if="currentUser.roles[0].name == 'Administrator'" class="modal-item">
                                <label for="">Assign type:</label>
                                <select @change="assignType($event)">
                                    <option v-for="type in types" :key="type.id" :value="type.id" :selected="1">{{type.name}}</option>
                                </select>
                            </div>

                            <div v-if="currentUser.roles[0].name == 'Administrator'" class="modal-item">
                                <label>Assign Role</label>
                                <select @change="assignRole($event)" name="" id="">
                                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                                </select>
                            </div>
                            <div class="modal-item">
                                <label for="">Image:</label>
                                <input type="file" @change="imageChanged" placeholder="Photo:">
                                <span class="errors" v-if="errors.image"><i class="fa fa-warning"></i>Image is required</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="red-modal-button" @click="addUser">Submit</button>
                            <button class="red-modal-button" @click="modal = false">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div>
            <transition name="fade" mode="out-in">
                <CreateUserType
                    v-if="createType == true "
                    @close-type-modal ="closeUserTypeModal"
                    :types="types"
                />
            </transition>
            <transition name="fade" mode="out-in">
                <CreateRoom
                    v-if="createRoom == true "
                    @close-room-modal ="closeCreateRoomModal"
                />
            </transition>
        </div>
        <div class="table-div">
            <table class="guest-list-table">
                <thead>
                    <tr>
                        <th># ID</th>
                        <th>Image</th>
                        <th>Name and surname</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for ="user in users" :key="user.id">
                        <td>#{{user.id}}</td>
                        <td> <img width="30px" height="30px" :src="getImage(user.image)" alt=""> </td>
                        <td>{{(user.name)}} {{ user.surname }}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.type.name}}</td>
                        <td>{{user.roles[0].name}}</td>
                        <td><span class="fas fa-search" @click="userProfile(user.id)"></span></td>
                    </tr>     
                </tbody>
                <tfoot>
                    <pagination :meta="meta"  v-on:pagination:switched="getUsers"></pagination>
                </tfoot>
            </table>
        </div>

    </div>
</template>
<script>

import CreateUserType from './types/CreateUserType'
import CreateRoom from '../rooms/CreateRoom'
import Pagination from '../addtitonal_components/Pagination'

export default {
    name:'user-list',
    components: {
        Pagination,
        CreateUserType,
        CreateRoom
    },
    data() {
        return {
            types: [],
            createRoom: '',
            createType: '',
            modal: '',
            name: '',
            surname: '',
            p_id: '',
            jmbg: '',
            type_id: '',
            email: '',
            password: '',
            image: '',
            role: 'Visitor',
            errors: '',
            search: '',
        }
    },
    created() {
        this.$store.dispatch('getUsers')
        this.$store.dispatch('fetchAllRoles')
    },
    mounted() {
        this.fetchTypes()
    },
    computed:{
        users() {
            return this.$store.getters.getAllUsers
        },
        currentUser() {
            return this.$store.getters.getUser
        },
        userRole() {
            return this.$store.state.user_roles
        },
        meta() {
            return this.$store.getters.getUserMeta
        },
        roles() {
            return this.$store.getters.getAllRoles
        },
        token() {
            return this.$store.state.token
        }
    },
    methods: {
        createRoomModal() {
            this.createRoom = true
        },
        closeCreateRoomModal() {
            this.createRoom = false
        },
        userTypeModal() {
            this.createType = true
        },
        closeUserTypeModal() {
            this.createType = false
        },
        assignType(event) {
            const value = Number(event.target.value) 
                this.type_id = value
                console.log(value)
        },
        assignRole(event) {
        const value = Number(event.target.value) 
            switch(value) {
            case 1:
                this.role = "Staff"
                console.log(value,this.role)
                break;
            case 2:
                this.role = "Secretary"
                console.log(value,this.role)
                break;
            case 3:
                this.role = "GDPR-Admin"
                console.log(value,this.role)
                break;
            case  4:
                this.role = "Administrator"
                console.log(value,this.role)
                break;
            default:
                this.role = "Staff"
                console.log(value,this.role)
                break;
            }
        },
        getUsers(page = this.$route.query.params) {
            this.$store.dispatch('getUsers', page)
        },
        imageChanged(e) {
            console.log(e.target.files[0])
            let fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.image = e.target.result
            }
        },
        deleteUser(id) {
            this.$store.dispatch('deleteUser', id)
        },
        userProfile(id) {
            this.$router.push({path:`user/${id}`})
        },
        getImage(image) {
            return "/images/" + image
        },
        addUser() {
            this.$store.dispatch('register',{
                name: this.name,
                surname: this.surname,
                email: this.email,
                password: this.password,
                role: this.role,
                jmbg: this.jmbg,
                image: this.image,
                type_id: this.type_id
            })
            .then(response => {
                this.errors = []
                this.name = '',
                this.jmbg = '',
                this.p_id = '',
                this.surname = '',
                this.password = '',
                this.type_id = '',
                this.roleName = '',
                this.image = '',
                this.modal = false
                this.getUsers()
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        fetchTypes() {
            axios.get('/api/types?token=' + this.token) 
            .then(response => {
                this.types = response.data
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
    }
}
</script>

