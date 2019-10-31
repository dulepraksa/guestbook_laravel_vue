<template>
    <div>
        <table class="guest-visits-table">
            <thead>
                <tr>
                    <th>Time of arrival</th>
                    <th>Date of arrival</th>
                    <th>Left at</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="visit in visits" :key="visit.id">
                    <td>{{timeReformat(visit.arrived_at)}}</td>
                    <td>{{dateReformat(visit.arrived_at)}}</td>
                    <td>{{timeReformat(visit.left_at)}}</td>
                    <td>{{visit.description}}</td>
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

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}


import pagination from '../addtitonal_components/Pagination'
export default {
    components: {
        pagination
    },
    props: {
        userId: String
    },
    computed: {
        visits() {
            return this.$store.state.myVisits
        },
        meta() {
            return this.$store.getters.getUserVisitsMeta
        }
    },
    mounted() {
        this.fetchVisits()
    },
    methods: {
        closeVisit(visitId) {
            let leftAt = new Date();
            this.$store.dispatch('closeVisit',{
                id: visitId,
                left_at: leftAt
            })
        },
        timeReformat(timeString) {
            if (timeString != null) {
                let time = new Date(timeString);
                let newTime = zeroPadding(time.getHours(),2) + ':' + zeroPadding(time.getMinutes(),2)
                return newTime
            }
        },
        dateReformat(timeString) {
            let date = new Date(timeString);
            let newDate = zeroPadding(date.getDate(), 2 ) + '.' + zeroPadding(date.getMonth()+1, 2) + '.' + zeroPadding(date.getFullYear(), 4)
            return newDate
        },
        fetchVisits(page = this.$route.query.params){
            this.$store.dispatch('userVisits', this.userId , page)
            .then(response => {
                console.log(response)
            })
        }
    }
}
</script>

<style>

</style>
