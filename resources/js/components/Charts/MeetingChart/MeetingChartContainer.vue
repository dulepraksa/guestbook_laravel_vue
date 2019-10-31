<template>
    <div :style="myStyles">
        <MeetingChart
            v-if="loaded"
            :chartLabels="labels"
            :chartData="rows"
        />
    </div>
</template>

<script>
import MeetingChart from './MeetingChart'
export default {
    components: {
        MeetingChart
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
            labels: [],
            height: 200,
            width: 700,
        }
    },
    mounted() {
        this.getData()
    },
    methods: {
        getData() {
            this.loaded = false
            axios.get('/api/meetings/chartData?token=' + this.token)
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
                height: 'height',
                position: 'relative',
                margin: '0 auto',
                width: 'auto'
            }
        }
    }
}
</script>

<style>

</style>
