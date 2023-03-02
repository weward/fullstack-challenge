<script>

import { FilterMatchMode } from 'primevue/api';
import Button from 'primevue/button';
import Toolbar from 'primevue/toolbar';
import DataTable from 'primevue/datatable';
import InputText from 'primevue/inputtext';
import Column from 'primevue/column';
import Rating from 'primevue/rating';
import Dialog from 'primevue/dialog';

export default {
  components: {
    Button,
    DataTable,
    Column,
    Dialog,
  },
        
  data: () => ({
    apiResponse: null,
    weatherDialog: false,
    dialogResponse: [],
  }),

  created() {
    this.fetchData()
  },

  methods: {
    async fetchData() {
      const url = 'http://localhost/'
      this.apiResponse = await (await fetch(url)).json()
      this.apiResponse = await typeof this.apiResponse.users !== 'undefined' ? this.apiResponse.users : []
    },

    async openWeatherDialog(user) {
      const url = `http://localhost/${user.id}`
      this.dialogResponse = await (await fetch(url)).json()
      this.weatherDialog = true
    }
  }
}
</script>

<template>
  <div class="w-80 m-auto mb-60" >
    <div v-if="!apiResponse">
      Pinging the api...
    </div>

    <div v-if="apiResponse">
      
      <div class="card">

        <DataTable 
            ref="dt" 
            :value="apiResponse" 
            v-model:selection="apiResponse" 
            dataKey="id" 
            :paginator="false" 
            :rows="20" 
            responsiveLayout="scroll"
            stripedRows>

            <template #header>
                <div class="table-header flex flex-column md:flex-row md:justiify-content-between">
                  <h3 class="mb-2 md:m-0 p-as-md-center">Users</h3>
                </div>
            </template>

            <Column field="name" header="Name" :sortable="false" class="text-dark text-bold" style="min-width:12rem"></Column>
            <Column field="weather" header="Weather" :sortable="false"  class="text-dark" style="min-width:8rem"></Column>
            <Column field="temperature" header="Temperature" :sortable="false"  class="text-dark" style="min-width:12rem">
                <template #body="slotProps">
                    <span :class="'product-badge status-'">{{ slotProps.data.temperature }}</span>
                </template>
            </Column>
            <Column :exportable="false" style="min-width:8rem"  class="text-dark">
                <template #body="slotProps">
                    <Button icon="pi pi-eye" class="p-button-rounded p-button-success mr-2 text-bold" @click="openWeatherDialog(slotProps.data)" />
                </template>
            </Column>
        </DataTable>
      </div>

      <Dialog v-model:visible="weatherDialog" :style="{ width: '450px' }" header="Weather Details" :modal="true" class="p-fluid" :breakpoints="{'960px': '75vw', '640px': '90vw'}" >
          <div class="formgrid grid">
            <div v-for="res in dialogResponse" :key="res.id">
              <div class="tbl-item" v-if="res.value">
                <div class="tbl-label">{{ res.label }}</div>
                <div class="tbl-value">{{ res.value }}</div>
              </div>
            </div>
          </div>
          <template #footer>
              <Button label="Close" icon="pi pi-" class="p-button-text" @click="weatherDialog = false"/>
          </template>
      </Dialog>

    </div>
  </div>
</template>
<style lang="css">


</style>