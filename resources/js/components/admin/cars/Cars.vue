<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline">Cars</v-toolbar-title>
    </v-app-bar>
    <v-container class="py-8">
      <v-row>
        <v-col v-if="pageLoading == true" cols="12">
          <v-skeleton-loader
            class="mx-auto"
            max-width="100%"
            type="list-item-avatar-three-line, image, article"
          ></v-skeleton-loader>
        </v-col>
        <v-col v-else cols="12" class="py-5">
          <v-btn to="car/new" class="secondary mb-5">New Car</v-btn>
          <v-card>
            <v-card-title>
              <h4>Cars</h4>
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="data_lists"
              :search="search"
            >
              <template v-slot:[`item.status`]="{ item }">
                <v-chip
                  :class="`${ item.status == 'active' ? 'success' : item.status == 'disabled' ? 'error' : 'grey' }`"
                  >{{ item.status == 'active' ? 'Active' : item.status == 'disabled' ? 'Disabled' : 'Trashed' }}</v-chip
                >
              </template>
              <template v-slot:[`item.actions`]="{ item }">
                <v-btn
                  fab
                  x-small
                  depressed
                  @click="editData(item)"
                  class="transparent mr-1"
                >
                  <v-icon small> mdi-pencil </v-icon>
                </v-btn>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pageLoading: true,
      search: "",
      headers: [
        { text: "Plate No", value: "plate_no" },
        { text: "Cars", value: "title" },
        { text: "Year", value: "year" },
        { text: "Color", value: "color" },
        { text: "Chassis No.", value: "chassis_no" },
        { text: "Registration Exp.", value: "registration_expiry" },
        { text: "Insurance Exp.", value: "insurance_expiry" },
        { text: "KM Registered", value: "km" },
        { text: "Status", value: "status" },
        { text: "Actions", align: "end", value: "actions", sortable: false },
      ],
      data_lists: [],
    };
  },
  methods: {
    async getAllData() {
      const response = await axios.get("/d/cars/fetch/all");
      if(response.data){
        this.data_lists = Object.assign([], response.data.data);
      }
    },
    editData(obj) {
      this.$router.push({
        name: "EditCar",
        params: { id: obj.id },
      });
    },
  },
  created() {
    this.getAllData().then(() => {
      this.pageLoading = false;
    });
  },
};
</script>
