<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline">{{ pagetitle }} Car</v-toolbar-title>

      <template v-slot:extension>
        <v-tabs v-model="tab" background-color="primary" dark align-with-title>
          <v-tabs-slider color="black"></v-tabs-slider>

          <v-tab key="details"> DETAILS </v-tab>
          <v-tab v-if="pagetitle == 'edit'" key="history"> HISTORY </v-tab>
        </v-tabs>
      </template>
    </v-app-bar>
    <v-container class="pt-3 mx-4" v-if="pagetitle == 'edit'">
      <v-btn class="secondary" @click="newIncident">NEW INCIDENT</v-btn>
    </v-container>
    <v-container class="py-2">
      <v-tabs-items v-model="tab">
        <v-tab-item key="details">
          <v-card flat>
            <v-card-text>
              <v-row>
                <div class="col-12 col-md-8">
                  <ValidationObserver
                    ref="user_form_observer"
                    v-slot="{ valid }"
                  >
                    <v-form ref="form">
                      <v-card :loading="loading">
                        <v-card-title v-if="pagetitle !== 'edit'">
                          <h4>{{ cardTitle }}</h4>
                        </v-card-title>
                        <v-card-text>
                          <v-switch
                            style="max-width: 120px"
                            v-model="statusSwitch"
                            :color="`${
                              statusSwitch == true ? 'success' : 'grey'
                            }`"
                            :label="`${
                              statusSwitch == true ? 'Active' : 'Disabled'
                            }`"
                          ></v-switch>
                          <v-row>
                            <v-col md="6">
                              <ValidationProvider
                                v-slot="{ errors }"
                                rules="required"
                                name="Plate No."
                              >
                                <v-text-field
                                  dense
                                  v-model="formObj.plate_no"
                                  label="Plate No *"
                                  outlined
                                  hide-details
                                  required
                                  :error-messages="errors"
                                ></v-text-field>
                              </ValidationProvider>
                            </v-col>
                            <v-col md="6">
                              <ValidationProvider
                                v-slot="{ errors }"
                                rules="required"
                                name="color"
                              >
                                <v-text-field
                                  dense
                                  v-model="formObj.color"
                                  label="Color *"
                                  outlined
                                  required
                                  hide-details
                                  :error-messages="errors"
                                ></v-text-field>
                              </ValidationProvider>
                            </v-col>
                            <v-col md="6">
                              <ValidationProvider
                                v-slot="{ errors }"
                                rules="required"
                                name="title"
                              >
                                <v-text-field
                                  dense
                                  v-model="formObj.title"
                                  label="Make &amp; Model *"
                                  outlined
                                  required
                                  hide-details
                                  :error-messages="errors"
                                ></v-text-field>
                              </ValidationProvider>
                            </v-col>
                            <v-col md="6">
                              <ValidationProvider
                                v-slot="{ errors }"
                                rules="required"
                                name="chassis"
                              >
                                <v-text-field
                                  dense
                                  v-model="formObj.chassis_no"
                                  label="Chassis No. *"
                                  hide-details
                                  outlined
                                  :disabled="pagetitle == 'edit' ? true : false"
                                  required
                                  :error-messages="errors"
                                ></v-text-field>
                              </ValidationProvider>
                            </v-col>
                            <v-col md="6">
                              <ValidationProvider
                                v-slot="{ errors }"
                                rules="required"
                                name="kilometer"
                              >
                                <v-text-field
                                  dense
                                  v-model="formObj.km"
                                  :disabled="pagetitle == 'edit' ? true : false"
                                  label="Current KM *"
                                  outlined
                                  required
                                  type="number"
                                  hide-details
                                  :error-messages="errors"
                                ></v-text-field>
                              </ValidationProvider>
                            </v-col>
                            <v-col md="6">
                              <ValidationProvider
                                v-slot="{ errors }"
                                rules="required"
                                name="year"
                              > 
                                <v-text-field
                                  dense
                                  v-model="formObj.year"
                                  label="Year *"
                                  :disabled="pagetitle == 'edit' ? true : false"
                                  outlined
                                  required  
                                  type="text"
                                  hide-details
                                  maxlength="4"
                                  :error-messages="errors"
                                ></v-text-field>
                              </ValidationProvider>
                            </v-col>
                            <v-col md="6">
                              <v-text-field
                                dense
                                v-model="formObj.registration_expiry"
                                label="Reg Expiry *"
                                hide-details
                                outlined
                                type="date"
                              ></v-text-field>
                            </v-col>
                            <v-col md="6">
                              <v-text-field
                                dense
                                type="date"
                                v-model="formObj.insurance_expiry"
                                label="Insurance Expiry *"
                                hide-details
                                outlined
                              ></v-text-field>
                            </v-col>
                            <v-col md="12">
                              <small class="red--text"
                                >Note: Once created, you cannot edit the Current
                                KM, Chassis No. &amp; Year</small
                              >
                            </v-col>

                            <v-card-actions>
                              <v-btn
                                v-if="pagetitle == 'edit'"
                                text
                                color="error"
                                @click="deleteData()"
                                >delete</v-btn
                              >
                              <v-spacer></v-spacer>
                              <v-btn
                                class="primary"
                                :disabled="!valid"
                                @click="submit"
                                >Save</v-btn
                              >
                            </v-card-actions>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-form>
                  </ValidationObserver>
                </div>
              </v-row>
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item key="history">
          <v-card flat>
            <v-card-text>
              <h3 class="mb-2">
                {{ formObj.title }} ({{ formObj.plate_no }}) :
                {{ formObj.chassis_no }}
              </h3>
              <v-toolbar class="d-flex align-center">
                <div>Filter by:</div>
                <v-autocomplete
                  dense
                  :items="service"
                  label="Type"
                  outlined
                  clearable
                  hide-details
                  v-model="filterObj.type"
                  class="mx-2"
                ></v-autocomplete>
                <v-text-field
                  type="date"
                  dense
                  label="Start Date"
                  outlined
                  v-model="filterObj.start_date"
                  hide-details
                  class="mx-2"
                ></v-text-field>
                <v-text-field
                  type="date"
                  dense
                  label="End Date"
                  outlined
                  v-model="filterObj.end_date"
                  hide-details
                  class="mx-2"
                ></v-text-field>

                <v-btn class="primary small" @click="searchFilter"
                  >SEARCH</v-btn
                >
              </v-toolbar>
            </v-card-text>
          </v-card>

          <v-card v-if="searchHistory">
            <v-card-text>
              <v-toolbar class="mb-2">
                Results:
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
              </v-toolbar>

              <v-data-table
                class="elevation-1"
                :headers="headers"
                :items="searchData"
                :search="search"
              ></v-data-table>
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-container>
    <!-- actions and dialogs -->
    <snack-bar :snackbar-options="sbOptions"></snack-bar>
    <confirmation-dialog
      :conf-options="confOptions"
      @response="confResponse"
    ></confirmation-dialog>
  </div>
</template>
<script>
import {
  ValidationObserver,
  ValidationProvider,
} from "vee-validate/dist/vee-validate.full";
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  props: {
    objectdata: {
      type: Object,
      default: null,
    },
    pagetitle: {
      type: String,
      default: "new",
    },
  },
  data() {
    return {
      statusSwitch: true,

      searchHistory: false,
      searchData: [],
      rolesArray: ["admin", "staff"],
      actionSave: this.pagetitle,
      cardTitle: "Register Vehicle",
      service: ["Service", "Petrol", "Others"],
      headers: [
        {
          text: "Type",
          align: "start",
          value: "type",
        },
        { text: "Driver In", value: "driver_in.fullname" },
        { text: "Driver Out", value: "driver_out.fullname" },
        { text: "Date In", value: "date_in" },
        { text: "Date Out", value: "date_out" },
        { text: "KM In", value: "km_in" },
        { text: "KM Out", value: "km_out" },
        { text: "Cost", value: "cost" },
        { text: "Note", value: "remarks" },
      ],
      formObj: {},
      newStatus: "",
      search: "",
      // ui
      is_checking_mail: false,
      sbOptions: {},
      filterObj: {},
      confOptions: {},
      loading: this.objectdata ? true : false,
      tab: null,
    };
  },
  watch: {
    objectdata: {
      handler(val, oldVal) {
        if (val != oldVal) {
          this.formObj = Object.assign({}, val);
          this.statusSwitch = val.status == "active" ? true : false;
        }
        this.loading = false;
      },
      deep: true,
    },
  },
  methods: {
    newIncident: function () {
      this.$router
        .push("/d/car/new-incident/" + this.formObj.id)
        .catch((err) => {});
    },

    searchFilter: function () {
      this.loading = true;
      this.searchData = [];

      this.sbOptions = {
        status: true,
        type: "info",
        text: "Searching....",
      };

      if (this.filterObj.start_date && this.filterObj.end_date) {
        this.filterObj.car_id = this.formObj.id;
        this.searchHistory = true;

        axios
          .post("/d/car/fetch/incidents", this.filterObj)
          .then((response) => {
            if (response.data) {
              setTimeout(() => {
                this.searchData = Object.assign([], response.data.data);
                console.log(this.searchData);
                this.loading = false;
              }, 2000);
            }
          });
      } else {
        this.loading = false;
        this.sbOptions = {
          status: true,
          type: "error",
          text: "Select Start and End Date.",
        };
      }
    },

    submit() {
      this.loading = true;

      // Set status value
      this.formObj.status = this.statusSwitch == true ? "active" : "disabled";
      let dataForm = { data: this.formObj };

      if (this.formObj.id) {
        let bdata = this.formObj;
        delete bdata["created_at"];
        delete bdata["updated_at"];
        delete bdata["km"];
        delete bdata["year"];
        delete bdata["chassis_no"];
        dataForm = { data: bdata };
      }

      // Send data to save
      axios
        .post("/d/car/save", dataForm)
        .then((response) => {
          this.sbOptions = {
            status: true,
            type: "success",
            text: "Data has been saved",
          };
          if (this.pagetitle == "edit") {
            this.$emit("saved", true);
          } else {
            this.$nextTick(() => {
              this.loading = false;
              this.formObj = {};
              this.$refs.user_form_observer.reset();
              this.$router.push({ name: "Cars" });
            });
          }
        })
        .catch((err) => {
          this.loading = false;
          this.sbOptions = {
            status: true,
            type: "error",
            text: "Error saving data",
          };
        });
    },
    deleteData() {
      this.confOptions = {
        status: true,
        title: "Confirm",
        msg: "Please confirm that you want to delete this data.",
        btnTitle: "delete",
        action: "delete",
      };
    },
    confResponse(value) {
      if (value == true) {
        axios
          .get("/d/car/delete/" + this.formObj.id)
          .then((response) => {
            this.sbOptions = {
              status: true,
              type: "success",
              text: "Data has been deleted",
            };
            setTimeout(() => {
              this.$router.push({ name: "Cars" });
            }, 800);
          })
          .catch((err) => {
            console.log(err.response.data);
            this.loading = false;
            this.sbOptions = {
              status: true,
              type: "error",
              text: err.response.data.message,
            };
          });
      }
    },
  },
};
</script>
