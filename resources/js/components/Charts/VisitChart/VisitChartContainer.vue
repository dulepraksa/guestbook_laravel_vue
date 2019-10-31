<template>
    <div :style="myStyles">
        <VisitChart
            v-if="loaded"
            :chartLabels="labels"
            :chartData="rows"
        />
    </div>
</template>

<script>
import VisitChart from './VisitChart'
export default {
    components: {
        VisitChart
    },
    computed: {
        token() {
            return this.$store.state.token
        }
    },
    data() {
        return {
            loaded: false,
            rows: [],
            labels: []
        }
    },
    mounted() {
        this.getData()
    },
    methods: {
        getData() {
            this.loaded = false
            axios.get('/api/visits/chartData?token=' + this.token)
                .then(response => {
                    this.rows = response.data.numbers
                    this.labels = response.data.dates
    
                    console.log(response.data)
                    this.loaded = true
                })
                .catch(error => {
                    console.log(error)
                })
        },
        myStyles () {
            return {
                height: 'auto',
                position: 'relative',
                margin: '0 auto',
                width: 'auto',
            }
        }
    }
}
</script>

<style>

</style>
