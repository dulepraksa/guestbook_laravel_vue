<template>
    <div class="dashboard">
        <div class="dashboard-header">            
            <div class="dasboard-header-item">
                <div class="item-icon">
                    <span class="fa fa-map-marker"></span>
                </div>
                <div class="item-info">
                    <h2>Visits</h2>
                    <h4>{{visits}}</h4>
                </div>
            </div>

            <div class="dasboard-header-item">
                <div class="item-icon">
                    <span class="fa fa-handshake-o"></span>
                </div>
                <div class="item-info">
                    <h2>Meetings</h2>
                    <h4>{{meetings}}</h4>
                </div>
            </div>
        </div>
        <div class="dashboard-chart-container">
            <visit-chart-container/>
        </div>
        <div class="dashboard-chart-container">
            <meeting-chart-container/>
        </div>
    </div>
</template>

<script>
import VisitChartContainer from '../Charts/VisitChart/VisitChartContainer'
import MeetingChartContainer from '../Charts/MeetingChart/MeetingChartContainer'

export default {
    components: {
        MeetingChartContainer,VisitChartContainer
    },
    data() {
        return {
            visits: '',
            meetings: '',
            guests: '',
        }
    },
    computed: {
        token() {
            return this.$store.state.token
        }
    },
    mounted(){
        this.getCountedVisits()
        this.getCountedMeetings()
    },
    methods: {
        getCountedVisits() {
            axios.get('/api/visits/count?token=' + this.token)
                .then (response => {
                    this.visits = response.data
                })
        },
        getCountedMeetings() {
            axios.get('/api/meetings/count?token=' + this.token)
                .then (response => {
                    this.meetings = response.data
                })
        },
    }
}
</script>
<style>

</style>
