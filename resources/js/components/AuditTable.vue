<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Условное обозначение и номер меры</th>
        <th>Содержание мер системы защиты информации</th>
        <th>Оценка</th>
        <th>Способы реализации мер защиты информации</th>
        <th>Свидетельство</th>
      </tr>
    </thead>
    <tbody>
      <template v-for="(norm, index) in norms" :key="norm.id">
        <!-- Заголовок процесса -->
        <tr v-if="norm.process_name && (!index || norms[index-1].process_name !== norm.process_name)">
          <td></td>
          <td colspan="4">{{ norm.process_name }}</td>
          <td></td>
        </tr>
        
        <!-- Заголовок подпроцесса -->
        <tr v-if="norm.subprocess_name && (!index || norms[index-1].subprocess_name !== norm.subprocess_name)">
          <td></td>
          <td colspan="4">{{ norm.subprocess_name }}</td>
          <td></td>
        </tr>
        
        <!-- Строка нормы -->
        <tr>
          <td>
            {{ index + 1 }}
            <label :class="['btn', {'btn-success': norm.approved}]">
              <input type="checkbox" 
                     v-model="norm.approved"
                     @change="approveNorm(norm.id)">
            </label>
          </td>
          <td>{{ norm.code }}</td>
          <td>{{ norm.description }}</td>
          <td style="width: 200px;">
            <div class="btn-group btn-group-toggle">
              <label class="btn btn-secondary" :class="{'active': norm.score === 0}">
                <input type="radio" 
                       :name="'score_'+norm.id" 
                       :value="0"
                       v-model="norm.score"
                       @change="updateScore(norm.id, 0)"> 0
              </label>
              <label class="btn btn-secondary" :class="{'active': norm.score === 1}">
                <input type="radio" 
                       :name="'score_'+norm.id" 
                       :value="1"
                       v-model="norm.score"
                       @change="updateScore(norm.id, 1)"> 1
              </label>
              <label class="btn btn-secondary" :class="{'active': norm.score === -1}">
                <input type="radio" 
                       :name="'score_'+norm.id" 
                       :value="-1"
                       v-model="norm.score"
                       @change="updateScore(norm.id, -1)"> н/о
              </label>
            </div>
          </td>
          <td>{{ norm.implementation_type }}</td>
          <td>
            <button class="btn btn-primary" @click="addEvidence(norm.id)">
              Добавить
            </button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</template>

<script>
export default {
  props: {
    auditId: Number,
    organizationId: Number
  },
  
  data() {
    return {
      norms: []
    }
  },
  
  methods: {
    async loadNorms() {
      const response = await axios.get(`/organizations/${this.organizationId}/audits/${this.auditId}/norms`);
      this.norms = response.data;
    },
    
    async updateScore(normId, score) {
      await axios.post(`/organizations/${this.organizationId}/audits/${this.auditId}/assessments`, {
        norm_id: normId,
        score: score
      });
    },
    
    async approveNorm(normId) {
      await axios.post(`/organizations/${this.organizationId}/audits/${this.auditId}/approvals`, {
        norm_id: normId,
        approved: !this.norms.find(n => n.id === normId).approved
      });
    },
    
    addEvidence(normId) {
      // Открытие модального окна для добавления свидетельства
    }
  },
  
  mounted() {
    this.loadNorms();
  }
}
</script> 