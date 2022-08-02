<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline">New Incident</v-toolbar-title>
    </v-app-bar>

    <v-container class="py-2">
      <v-card flat>
        <v-card-text>
          <v-row>
            <div class="col-12 col-md-8">
              <ValidationObserver ref="user_form_observer" v-slot="{ valid }">
                <v-form ref="form">
                  <v-card :loading="loading">
                    <v-card-title>
                      <h4>{{ objectData.title }} : {{objectData.chassis_no}}</h4>
                    </v-card-title>
                    <v-card-text>
                      <v-row>
                        <v-col md="6" sm="12">
                          <ValidationProvider
                            v-slot="{ errors }"
                            rules="required"
                            name="TYPE"
                          >
                            <v-autocomplete
                              dense
                              :items="service"
                              v-model="formObj.type"
                              label="TYPE *"
                              outlined
                              hide-details
                              required
                              :error-messages="errors"
                            ></v-autocomplete>
                          </ValidationProvider>
                        </v-col>
                        <v-col md="6" sm="0"></v-col>
                        <v-col md="6" sm="12"> 
                            <v-text-field
                              dense
                              type="date"
                              v-model="formObj.date_in"
                              label="DATE IN *"
                              outlined
                              hide-details
                              required
                              clearable
                              
                            ></v-text-field> 
                        </v-col>
                        <v-col md="6" sm="12"> 
                            <v-text-field
                              dense
                              type="date"
                              v-model="formObj.date_out"
                              label="DATE OUT"
                              outlined
                              clearable 
                              hide-details 
                            ></v-text-field> 
                        </v-col>
                        <v-col md="6" sm="12"> 
                            <v-text-field
                              dense
                              v-model="formObj.km_in"
                              label="KM IN *"
                              outlined
                              required
                              hide-details
                              
                            ></v-text-field> 
                        </v-col>
                        <v-col md="6" sm="12"> 
                            <v-text-field
                              dense
                              v-model="formObj.km_out"
                              label="KM OUT"
                              hide-details
                              outlined 
                              small 
                            ></v-text-field> 
                        </v-col>

                        <v-col md="6" sm="12"> 
                            <v-autocomplete
                              dense
                              :items="driverObj"
                                item-value="id"
                                item-text="fullname"
                              v-model="formObj.driver_in"
                              label="DRIVER IN *"
                              hide-details
                              small
                              clearable
                              outlined 
                            ></v-autocomplete> 
                        </v-col>

                        <v-col md="6" sm="12"> 
                            <v-autocomplete
                              dense
                              :items="driverObj"
                               item-value="id"
                                item-text="fullname"
                              v-model="formObj.driver_out"
                              label="DRIVER OUT *"
                              hide-details
                              clearable
                              outlined
                              required
                              small
                            
                            ></v-autocomplete> 
                        </v-col>
                        <v-col md="6" sm="12">
                          <v-text-field
                            dense
                            v-model="formObj.cost"
                            label="COST (AED) *"
                            type="number"
                            hide-details
                            outlined
                            required
                            small
                          ></v-text-field>
                        </v-col>
                        <v-col md="12">
                          <v-textarea
                            outlined
                            label="Remarks"
                            hide-details
                            v-model="formObj.remarks"
                          ></v-textarea>
                        </v-col>
                      </v-row>
                      <v-row class="mt-0 pt-0">
                        <v-col md="12">
                          <small
                            >Data will be shown in history tab. Data will not be
                            edited once it has been created.</small
                          >
                        </v-col>
                        <v-col md="12">
                          <v-btn
                            class="primary"
                            small
                            :disabled="!valid"
                            @click="submit"
                            >Submit</v-btn
                          >
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </v-form>
              </ValidationObserver>
            </div>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>
    <!-- actions and dialogs -->
    <snack-bar :snackbar-options="sbOptions"></snack-bar>
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
  data() {
    return {
      storage: localStorage,
      statusSwitch: true,
      rolesArray: ["admin", "staff"],
      actionSave: this.pagetitle,
      cardTitle: "Register Vehicle",
      service: ["Service", "Petrol", "Others"],
      formObj: {},
      objectData: {},
      driverObj: [],
      newStatus: "",
      // ui

      sbOptions: {}, 
      loading: false,
      tab: null,
    };
  },

  methods: {
    submit() {
      this.loading = true;

      // Set status value
     this.formObj.car_id = this.$route.params.id;
      let dataForm = { data: this.formObj }; 
      
      // Send data to save
      axios
        .post("/d/car/incident/save", dataForm)
        .then((response) => {
          this.sbOptions = {
            status: true,
            type: "success",
            text: "Data has been saved",
          };

            setTimeout(() => { 
            
            this.$nextTick(() => {
              this.loading = false;
              this.formObj = {};
              this.$refs.user_form_observer.reset();

               this.$router
                  .push(
                    "/d/car/edit/" + this.$route.params.id
                  )
                  .catch((err) => {});
            });

            }, 1500);
          
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

    fetchDrivers: async function () {
      await axios.get("/d/driver/fetch/all").then((res) => {
        if (res.data) {
            this.driverObj = res.data;
        }
      });
    },

    fetchCar: async function(){
        axios
        .get("/d/car/get/" + this.$route.params.id)
        .then((response) => {
           
          this.objectData = Object.assign({}, response.data);
        })
        .catch((err) => {});
    }
  },

  created() {
    this.fetchCar().then(()=>{
          this.fetchDrivers();
    })
  },
};
</script>
