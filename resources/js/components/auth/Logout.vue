<template>
    
</template>

<script>
export default {
    name:'logout',
    computed: {
        token() {
            return this.$store.state.token
        }
    },
    created() {
        if( this.token ) {
            this.$store.dispatch('logout')
            .then ( () =>
                    this.$router.push('/home')
            )
        } else {
            this.$router.push('/login')
        }
            
    }
}
</script>
