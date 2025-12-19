<template>
  <div class="p-6">
    <h2 class="text-2xl mb-4">{{ $t('dashboard.title') }}</h2>
    <div class="grid grid-cols-3 gap-4">
      <div class="card p-4">
        <h3>{{ $t('dashboard.pending_requests') }}</h3>
        <p class="text-3xl">{{ counts.pending }}</p>
      </div>
      <div class="card p-4">
        <h3>{{ $t('dashboard.approved_requests') }}</h3>
        <p class="text-3xl">{{ counts.approved }}</p>
      </div>
      <div class="card p-4">
        <h3>{{ $t('dashboard.total_requests') }}</h3>
        <p class="text-3xl">{{ counts.total }}</p>
      </div>
    </div>

    <div class="mt-6">
      <h3>{{ $t('dashboard.recent') }}</h3>
      <ul>
        <li v-for="r in recent" :key="r.id">{{ r.patient_name }} — {{ r.request_type }} — {{ r.status }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  data() {
    return {
      counts: {
        pending: 0,
        approved: 0,
        total: 0
      },
      recent: []
    };
  },
  mounted() {
    // Load initial counts via API
    axios.get('/api/dashboard/summary').then(resp => {
      this.counts = resp.data.counts;
      this.recent = resp.data.recent;
    });

    // Listen to broadcasts
    if (window.Echo) {
      window.Echo.channel('requests-channel')
        .listen('.RequestCreated', (e) => {
          // Update counts and recent list
          this.counts.pending += 1;
          this.counts.total += 1;
          this.recent.unshift({ id: e.requestId, patient_name: e.patientName, request_type: e.requestType, status: 'pending' });
          if (this.recent.length > 10) this.recent.pop();
        });

      // Example listener for approvals (you can implement RequestApproved event similarly)
      window.Echo.channel('requests-channel')
        .listen('.RequestApproved', (e) => {
          this.counts.approved += 1;
          if (this.counts.pending > 0) this.counts.pending -= 1;
          // update recent status
          const item = this.recent.find(r => r.id === e.requestId);
          if (item) item.status = 'approved';
        });
    }
  }
};
</script>

<style scoped>
.card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
</style>