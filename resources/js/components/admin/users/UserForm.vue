<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline">{{ pagetitle }} User</v-toolbar-title>
    </v-app-bar>
    <v-container class="py-8">
       <ValidationObserver ref="user_form_observer" v-slot="{ valid }">
            <v-form ref="form">
      <v-row>
        <div class="col-12 col-md-9">
         
              <v-card :loading="loading">
                <v-card-title>
                  <h4>{{ cardTitle }}</h4>
                </v-card-title>
                <v-card-text> 
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="eCode"
                  >
                    <v-text-field
                      dense
                      outlined
                      v-model="usersObj.ecode"
                      label="eCode *"
                      :error-messages="errors"
                      required
                    ></v-text-field>
                  </ValidationProvider>
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="Full Name"
                  >
                    <v-text-field
                      dense
                      outlined
                      v-model="usersObj.fullname"
                      label="Full Name *"
                      :error-messages="errors"
                      required
                    ></v-text-field>
                  </ValidationProvider>
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="Birth Date"
                  >
                    <v-menu
                      v-model="menu1"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="auto"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="usersObj.date_birth"
                          label="Date of Birth *"
                          readonly
                          :error-messages="errors"
                          dense
                          outlined
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="usersObj.date_birth"
                        @input="menu1 = false"
                      ></v-date-picker>
                    </v-menu>
                  </ValidationProvider>
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="Date of Joining"
                  >
                    <v-menu
                      v-model="menu2"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="auto"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="usersObj.date_joining"
                          label="Date of Joining *"
                          readonly
                          :error-messages="errors"
                          dense
                          outlined
                          v-bind="attrs"
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="usersObj.date_joining"
                        @input="menu2 = false"
                      ></v-date-picker>
                    </v-menu>
                  </ValidationProvider>
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="email|required"
                    name="Email"
                  >
                    <div class="d-flex">
                      <v-text-field
                        autocomplete="false"
                        dense
                        outlined
                        v-model="usersObj.email"
                        label="Email"
                        :error-messages="emailExisted ? emailExisted : errors"
                        required
                      ></v-text-field>
                      <v-progress-circular
                        v-if="is_checking_mail == true"
                        :size="25"
                        :width="2"
                        indeterminate
                        color="primary"
                        class="ml-3 mt-2"
                      ></v-progress-circular>
                    </div>
                  </ValidationProvider>
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="Company"
                  >
                    <v-autocomplete
                      dense
                      v-model="usersObj.company"
                      :items="companies"
                      label="Company *"
                      outlined
                      item-value="id"
                      item-text="title"
                      required
                      :error-messages="errors"
                    >
                    </v-autocomplete>
                  </ValidationProvider>
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="Department"
                  >
                    <v-autocomplete
                      dense
                      v-model="usersObj.department"
                      :items="departments"
                      label="Department *"
                      item-value="id"
                      item-text="title"
                      outlined
                      required
                      :error-messages="errors"
                    >
                    </v-autocomplete>
                  </ValidationProvider>
                  <v-text-field
                    dense
                    outlined
                    v-model="usersObj.position"
                    label="Position"
                  ></v-text-field>

                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="role"
                  >
                    <v-autocomplete
                      dense
                      v-model="usersObj.role"
                      :items="rolesArray"
                      label="Role *"
                      @change="handleRole"
                      outlined
                      required
                      :error-messages="errors"
                    >
                    </v-autocomplete>
                  </ValidationProvider>
                  <v-row v-if="isAdmin || isModerator">
                  <v-col cols="2" sm="12" md="2"  v-for="item in accessSlug" :key="item">
                    <v-checkbox
                      v-model="accessPage"
                      :label="item" 
                      :value="item"
                      hide-details
                    ></v-checkbox>
                  </v-col>
                  </v-row> 
                </v-card-text>
              </v-card>
           
        </div>
        <div class="col-12 col-md-3">
             <v-card :loading="loading">
                        <v-card-text>
                           <v-switch
                    style="max-width: 120px"
                    v-model="statusSwitch"
                    :color="`${statusSwitch == true ? 'success' : 'grey'}`"
                    :label="`${statusSwitch == true ? 'Active' : 'Disabled'}`"
                  ></v-switch>
                          <div class="d-flex">
                             <v-btn
                              class="primary"
                              :disabled="!valid"
                              small
                              @click="submit"
                              >Save</v-btn
                            >
                            <v-spacer></v-spacer>
                           
                            <v-btn
                              v-if="pagetitle == 'edit' && auth.role == 'superadmin'"
                              text
                              color="error"
                              small
                              @click="deleteUser()"
                              >delete</v-btn
                            >
                          </div>
                        </v-card-text>
                      </v-card>
                      <v-divider></v-divider>
          <UploadImage
            :selectedImage="selectedImage"
            @selected="imageResponse"
          />
          <div class="mt-2" v-if="pagetitle === 'edit'">
            <ChangePassword :user="userdata" />
          </div>
        </div> 
      </v-row>
       </v-form>
          </ValidationObserver>
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
import ChangePassword from "./ChangePassword.vue";
import UploadImage from "../studio/UploadImage";
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    ChangePassword,
    UploadImage,
  },
  props: {
    userdata: {
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
      auth: this.$store.state.authUser.userObject,
      menu2: false,
      menu1: false,
      statusSwitch: true,
      rolesArray: ["admin", "moderator", "normal"],
      companies: [],
      departments: [],
      actionSave: this.pagetitle,
      cardTitle: "New user",
      emailExisted: "",
      usersObj: { role: "normal" },
      origEmail: this.userdata ? this.userdata.email : "",
      origEcode: "",
      newStatus: "",
      // ui
      is_checking_mail: false,
      sbOptions: {},
      confOptions: {},
      loading: this.userdata ? true : false,
      email_already_exists: false,
      selectedImage: {},
      isModerator: false,
      isAdmin: false,
      accessPage: [],
      accessSlug: [
        "medias",
        "events",
        "polls",
        "users",
        "trainings",
        "careers",
        "package_ads",
        "cashless",
        "suggestions",
        "marketplace",
      ],
    };
  },
  watch: {
    userdata: {
      handler(val, oldVal) {
        if (val != oldVal) {
          this.usersObj = Object.assign({}, val);
          this.origEmail = val.email;
          this.cardTitle = val.full_name;

          if (val.role == "admin") {
            this.isAdmin = true;
          } else if (val.role == "moderator") {
            this.isModerator = true;
          }

          this.statusSwitch = val.status == "active" ? true : false;
          if (this.usersObj.images && this.usersObj.images.length > 0) {
            this.selectedImage = this.usersObj.images[0];
          }
          if (this.usersObj.profile) {
            this.usersObj.ecode = this.usersObj.profile.ecode;
            this.origEcode = this.usersObj.profile.ecode;
            this.usersObj.fullname = this.usersObj.profile.fullname;
            this.usersObj.date_birth = this.usersObj.profile.date_birth;
            this.usersObj.date_joining = this.usersObj.profile.date_joining;
            this.usersObj.position = this.usersObj.profile.position;
            this.usersObj.company = this.usersObj.profile.company_id;
            this.usersObj.department = this.usersObj.profile.department_id;
          }
          if(this.usersObj.access){
            let getCurrentAccess = [];
            
            this.usersObj.access.map((o,i)=>{
              getCurrentAccess[i] = o.slug;
            })
            this.accessPage = getCurrentAccess;
          }
        }
        this.loading = false;
      },
      deep: true,
    },
  },
  methods: {
    handleRole: function (v) {
      if (v === "admin") {
        this.isAdmin = true;
      } else if (v == "moderator") {
        this.isModerator = true;
      } else {
        this.isAdmin = false;
        this.isModerator = false;
      }
    },
    imageResponse: function (v) {
      this.selectedImage = v;
    },
    async emailCheck() {
      this.is_checking_mail = true;
      if (
        this.usersObj.email != this.origEmail &&
        this.origEcode != this.usersObj.ecode
      ) {
        await axios
          .post("/d/admin/user/check/email", {
            email: this.usersObj.email,
            currentMail: this.origEmail,
            ecode: this.usersObj.ecode,
            origEcode: this.origEcode,
          })
          .then((response) => {
            console.log("Mail Checking... ", response.data);
            if (response.data.email_existed == true) {
              this.email_already_exists = true;
              this.emailExisted = "Email/eCode already registered"; // Set error message
            } else {
              this.email_already_exists = false;
              this.emailExisted = ""; // Empty error message
            }
            this.is_checking_mail = false;
          })
          .catch((err) => {
            console.log("Email Check Error");
            console.log(err.response);
            this.is_checking_mail = false;
          });
      } else {
        this.is_checking_mail = false;
      }
    },
    submit() {
      
      this.sbOptions = {
        status: true,
        type: "info",
        text: "Please wait...",
      };
      this.loading = true;
      let images = this.selectedImage.hasOwnProperty("id")
        ? this.selectedImage.id
        : null;
      // Set status value
      this.usersObj.status = this.statusSwitch == true ? "active" : "disabled";
      this.usersObj.image = images;
      // Check if the email is changed
      let uObject = {
        status: this.usersObj.status,
        email: this.usersObj.email,
        username: this.usersObj.ecode,
        role: this.usersObj.role,
      };
      if (this.usersObj.email == this.origEmail) {
        delete this.usersObj["email"];
        uObject = { status: this.usersObj.status, role: this.usersObj.role };
      }

      if (this.usersObj.ecode == this.origEcode) {
        delete this.usersObj["ecode"];
        uObject = { status: this.usersObj.status, role: this.usersObj.role };
      }
      let dData = {
        nUser: uObject,
        nProfile: {
          company_id: this.usersObj.company,
          department_id: this.usersObj.department,
          date_birth: this.usersObj.date_birth,
          date_joining: this.usersObj.date_joining,
          ecode: this.usersObj.ecode,
          fullname: this.usersObj.fullname,
          position: this.usersObj.position,
        },
        id: this.usersObj ? this.usersObj.id : null,
        image: images,
        access: this.accessPage
      };

      // Check the email if exists
      this.emailCheck().then(() => {
        // Set error message if email already exists
        if (this.email_already_exists == true) {
          this.sbOptions = {
            status: true,
            type: "error",
            text: "Email/eCode already registered",
          };
          this.loading = false;
          return false;
        }

        // Send data to save
        axios
          .post("/d/admin/user/save", dData)
          .then((response) => {
            this.sbOptions = {
              status: true,
              type: "success",
              text: "User has been saved",
            };
            setTimeout(() => {
              if (this.pagetitle == "edit") {
                this.$emit("saved", true);
              } else {
                this.$nextTick(() => {
                  this.loading = false;
                  this.usersObj = {};
                  this.$refs.user_form_observer.reset();
                  this.$router.push({ name: "Users" });
                });
              }
            }, 800);
          })
          .catch((err) => {
            console.log(err.response);
            this.loading = false;
            this.sbOptions = {
              status: true,
              type: "error",
              text: "Email/Username already registered",
            };
          });
      });
    },
    deleteUser() {
      this.confOptions = {
        status: true,
        title: "Confirm",
        msg: "Please confirm that you want to delete this account.",
        btnTitle: "delete",
        action: "delete",
      };
    },
    confResponse(value) {
      if (value == true) {
        axios
          .post("/d/user/delete/" + this.usersObj.id)
          .then((response) => {
            this.$emit("saved", true);
            this.sbOptions = {
              status: true,
              type: "success",
              text: "User has been deleted",
            };
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
    fetchCompany: async function () {
      await axios
        .get("/d/admin/fetch/non-paginate/companies")
        .then((response) => {
          console.log(response.data);
          this.companies = Object.assign([], response.data);
        });
    },
    fetchDepartment: async function () {
      await axios
        .get("/d/admin/fetch/non-paginate/departments")
        .then((response) => {
          this.departments = Object.assign([], response.data);
        });
    },
  },
  mounted() {
    this.fetchCompany().then(() => {
      this.fetchDepartment();
    });
  },
};
</script>