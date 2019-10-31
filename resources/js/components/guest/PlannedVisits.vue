<template>
    <div>
        <table class="guest-visits-table">
            <thead>
                <tr>
                    <th>Planned Time</th>
                    <th>Planned date arrival</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="visit in visits" :key="visit.id">
                    <td>{{timeReformat(visit.planned_time)}}</td>
                    <td>{{dateReformat(visit.planned_time)}}</td>
                    <td>{{visit.description}}</td>
                    <td><a @click.prevent="update(visit.id)">Make visit</a></td>
                </tr>     
            </tbody>
            <tfoot>
                <pagination
                :meta="meta"
                v-on:pagination:switched='fetchVisits'
                />
            </tfoot>
        </table>
    </div>
</template>

<script>
import pagination from '../addtitonal_components/Pagination'
export default {
    components: {
        pagination
    },
    props: {
        guestId: String
    },
    computed: {
        visits() {
            return this.$store.state.visits
        },
        meta() {
            return this.$store.state.guestVisitsPlannedMeta
        }
    },
    mounted() {
        this.fetchVisits()
    },
    methods:{
        update(id){

        },
        fetchVisits(page = this.$route.query.params) {
            this.$store.dispatch('plannedVisitsForGuest', this.guestId, page = this.$route.query.params)
        },
        timeReformat(timeString) {
            if (timeString != null) {
                let time = new Date(timeString);
                let newTime = time.getHours() + ':' + time.getMinutes()
                return newTime
            }
        },
        dateReformat(timeString) {
            let date = new Date(timeString);
            let newDate = date.getDay() + '/' + date.getMonth() + '/' + date.getFullYear() 
            return newDate
        },
        update(visitId) {
            const date = new Date()
            this.$store.dispatch('makeNormalVisit', {
                id: visitId,
                is_planned: false,
                planned_time: null,
                arrived_at: date,
                description: 'Was planned'
            })
        }
    }
}
</script>

<style>

</style>
