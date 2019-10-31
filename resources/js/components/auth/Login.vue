<template>
<div>
    <div class="sign-in">
            <img src="http://www.tiacgroup.com/wp-content/uploads/2015/06/TIAC-logo.png" alt="">
        <div class="sign-in-header">
            <h3>Login</h3>
        </div>
        <div class="sign-in-body">
            <div class="sign-in-input-container">
                <div class="sign-in-input">      
                    <i class="fa fa-user icon"></i>
                    <input type="text" name="" id="" placeholder="Enter your e-mail here..." v-model="email">
                </div>
                    <span class="errors" v-if="errors.email">{{errors.email[0]}}</span>
            </div>
            <div class="sign-in-input-container">
                <div class="sign-in-input">            
                    <i class="fa fa-key icon"></i>
                    <input type="password" name="" id="" placeholder="Enter your password here..." v-model="password">
                </div>
                    <span class="errors" v-if="errors.password">{{errors.password[0]}}</span>
            </div>
        </div>
        <div class="sign-in-footer">
            <button class="login-button"  v-on:click="login" >Login</button>
        </div>
    </div>
</div>
</template>
<script>
export default {
    name:'login',
    data() {
        return{
            email:'',
            password:'',
            errors:[]
        }
    },
    methods: {
        login() {
            this.$store.dispatch('getToken',{
                email: this.email,
                password: this.password
            })
                .then(() =>
                    this.$router.push('/dashboard')
                )
                .catch(error =>  {
                    console.log(error)
                    this.errors = error.response.data.errors
                })
        },
    }
}
</script>

<style>
    #app {
        background: rgb(128,100,100);
background: radial-gradient(circle, rgba(128,100,100,1) 0%, rgba(210,61,51,1) 100%);
    }
</style>


