<template>
    <div class="d-flex justify-content-center">
        <div class="card">
            <div class="card-header">
                <h3>Sign up</h3>
                <div class="card-body">
                    <div class="input-group form-group">
                        <input type="text" class="form-control" placeholder="Name" v-model="name">
                    </div>
                    <div class="input-group form-group">
                        <input type="text" class="form-control" placeholder="Surname" v-model="surname">
                    </div>
                    <div class="input-group form-group">
                        <input type="text" class="form-control" placeholder="E-mail" v-model="email">
                    </div>
                    <div class="input-group form-group">
                        <input type="password" class="form-control" placeholder="Password" v-model="password">
                    </div>
                    <div class="input-group form-group">
                        <input type="file" class="form-control" @change="imageChanged">
                    </div>
                    <div class="form-group">
                        <button @click="register" type="submit" class="btn btn-primary btn-block float-right">
                            Register
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    
    name:'register',
    data() {
        return{
            name: '',
            surname: '',
            email: '',
            password: '',               
            image: ''
        }
    },
    methods: {
        imageChanged(e) {
            console.log(e.target.files[0])
            let fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.image = e.target.result
            }
        },
        register() {
            this.$store.dispatch('register',{
                name: this.name,
                surname: this.surname,
                email: this.email,
                password: this.password,
                image: this.image
            })
        }
    }
}
</script>
