<template>
    <div class="create-meeting-modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Create user</h3>
                </div>
                <div class="modal-body">
                    <div v-if="image" class="modal-image" style="align-items:center;">
                        <img :src="image"  alt="">
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

                    <div v-if="userRole[0].name == 'Administrator'" class="modal-item">
                        <label for="">Assign type:</label>
                        <select @change="assignType($event)">
                            <option v-for="type in types" :key="type.id" :value="type.id" :selected="1">{{type.name}}</option>
                        </select>
                    </div>

                    <div v-if="userRole[0].name == 'Administrator'" class="modal-item">
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
                    <button class="red-modal-button" @click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        modal: Boolean
    },
    data() {
        return {
            name: '',
            surname: '',
            p_id: '',
            jmbg: '',
            type_id: 1,
            email: '',
            password: '',
            image: '',
            role: 'Visitor',
            errors:[],
            types: []
        }
    },
    computed: {
        token() {
            return this.$store.state.token
        },
        userRole() {
            return this.$store.state.user_roles
        },
        roles() {
            return this.$store.getters.getAllRoles
        },
    },
    mounted() {
        this.fetchTypes()
        console.log(this.role)
        console.log(this.type_id)
    },
    methods: {
        closeModal() {
            this.$emit('close-modal')
                this.errors = []
                this.name = ''
                this.jmbg = ''
                this.p_id = ''
                this.surname = ''
                this.password = ''
                this.type_id = ''
                this.image = ''
        },
        assignRole(event) {
        const value = Number(event.target.value) 
            switch(value) {
            case 1:
                this.role = "Visitor"
                console.log(value,this.role)
                break;
            case 2:
                this.role = "Staff"
                console.log(value,this.role)
                break;
            case 3:
                this.role = "Secretary"
                console.log(value,this.role)
                break;
            case 4:
                this.role = "GDPR-Admin"
                console.log(value,this.role)
                break;
            case 5:
                this.role = "Administrator"
                console.log(value,this.role)
                break;
            default:
                this.role = "Staff"
                console.log(value,this.role)
                break;
            }
        },
        imageChanged(e) {
            console.log(e.target.files[0])
            let fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.image = e.target.result
            }
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
                this.role = ''
                this.image = '',
                this.modal = false
                this.closeModal()
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
