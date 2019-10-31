<script>

import { Line } from 'vue-chartjs'
export default {
  extends: Line,
  props: {
    chartData: Array,
    chartLabels: Array,
  },
  mounted() {
    this.setChart()
  },
  methods: {
    setChart() {
      this.renderChart({
        labels: this.chartData,
        datasets:[{
          label: 'Weekly meetings', backgroundColor: '#C70D01', data:this.chartLabels
        }]
      },{responsive: true, maintainAspectRatio: false})
    }
  },
}
</script>
