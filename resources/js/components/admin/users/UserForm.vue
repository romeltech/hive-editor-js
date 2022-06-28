<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline">{{ pagetitle }} User</v-toolbar-title>
    </v-app-bar>
    <v-container class="py-8">
      <v-row>
        <div class="col-12 col-md-8">
          <ValidationObserver ref="user_form_observer" v-slot="{ valid }">
            <v-form ref="form">
              <v-card :loading="loading">
                <v-card-title>
                  <h4>Edit {{ cardTitle }}</h4>
                </v-card-title>
                <v-card-text>
                  <v-switch
                    style="max-width: 120px"
                    v-model="statusSwitch"
                    :color="`${statusSwitch == true ? 'success' : 'grey'}`"
                    :label="`${statusSwitch == true ? 'Active' : 'Disabled'}`"
                  ></v-switch>
                  <!-- <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="Username"
                  >
                    <div class="d-flex">
                      <v-text-field
                        autofocus
                        dense
                        outlined
                        v-model="usersObj.username"
                        label="Username *"
                        :error-messages="errors"
                        required
                      ></v-text-field>
                    </div>
                  </ValidationProvider> -->
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required"
                    name="Full Name"
                  >
                    <v-text-field
                      dense
                      outlined
                      v-model="usersObj.full_name"
                      label="Full Name *"
                      :error-messages="errors"
                      required
                    ></v-text-field>
                  </ValidationProvider>
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="email"
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
                    name="role"
                  >
                    <v-autocomplete
                      dense
                      v-model="usersObj.role"
                      :items="rolesArray"
                      label="Role *"
                      outlined
                      required
                      :error-messages="errors"
                    >
                    </v-autocomplete>
                  </ValidationProvider>
                  <ValidationProvider v-slot="{ errors }" rules="" name="Phone">
                    <v-text-field
                      dense
                      outlined
                      v-model="usersObj.phone"
                      label="Phone"
                      :error-messages="errors"
                    ></v-text-field>
                  </ValidationProvider>
                  <div v-if="pagetitle !== 'edit'">
                    <ValidationProvider
                      v-slot="{ errors }"
                      :rules="`${pagetitle == 'edit' ? '' : 'required|min:9'}`"
                      name="Password"
                    >
                      <v-text-field
                        dense
                        type="password"
                        v-model="usersObj.password"
                        label="Password *"
                        outlined
                        :required="pagetitle == 'edit' ? false : true"
                        name="password"
                        :error-messages="errors"
                      ></v-text-field>
                    </ValidationProvider>
                    <ValidationProvider
                      v-slot="{ errors }"
                      rules="required|confirmed:Password"
                      name="Password Confirmation"
                    >
                      <v-text-field
                        dense
                        type="password"
                        v-model="usersObj.password_confirmation"
                        label="Confirm Password *"
                        name="password_confirmation"
                        required
                        outlined
                        :error-messages="errors"
                      ></v-text-field>
                    </ValidationProvider>
                  </div>
                  <v-card-actions>
                    <v-btn
                      v-if="pagetitle == 'edit'"
                      text
                      color="error"
                      @click="deleteUser()"
                      >delete</v-btn
                    >
                    <v-spacer></v-spacer>
                    <v-btn class="primary" :disabled="!valid" @click="submit"
                      >Save</v-btn
                    >
                  </v-card-actions>
                </v-card-text>
              </v-card>
            </v-form>
          </ValidationObserver>
        </div>
        <div v-if="pagetitle === 'edit'" class="col-12 col-md-4">
          <ChangePassword :user="userdata" />
        </div>
      </v-row>
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
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    ChangePassword,
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
      statusSwitch: true,
      rolesArray: ["admin", "staff"],
      actionSave: this.pagetitle,
      cardTitle: "New user",
      emailExisted: "",
      usersObj: {},
      origEmail: this.userdata ? this.userdata.email : "",
      newStatus: "",
      // ui
      is_checking_mail: false,
      sbOptions: {},
      confOptions: {},
      loading: this.userdata ? true : false,
      email_already_exists: false,
    };
  },
  watch: {
    userdata: {
      handler(val, oldVal) {
        if (val != oldVal) {
          this.usersObj = Object.assign({}, val);
          this.origEmail = val.email;
          this.cardTitle = val.full_name;
          this.statusSwitch = val.status == "active" ? true : false;
        }
        this.loading = false;
      },
      deep: true,
    },
  },
  methods: {
    async emailCheck() {
      if (this.usersObj.email) {
        this.is_checking_mail = true;
        await axios
          .post("/d/user/check/email", {
            email: this.usersObj.email,
          })
          .then((response) => {
            if (response.data.email_existed == true) {
              this.email_already_exists = true;
              this.emailExisted = "Email already registered"; // Set error message
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
      }
    },
    submit() {
      this.loading = true;

      // Set status value
      this.usersObj.status = this.statusSwitch == true ? "active" : "disabled";

      // Check if the email is changed
      if (this.usersObj.email == this.origEmail) {
        delete this.usersObj["email"];
      }

      // Check the email if exists
      this.emailCheck().then(() => {
        // Set error message if email already exists
        if (this.email_already_exists == true) {
          this.sbOptions = {
            status: true,
            type: "error",
            text: "Email already registered",
          };
          this.loading = false;
          return false;
        }

        // Send data to save
        axios
          .post("/d/user/save", this.usersObj)
          .then((response) => {
            this.sbOptions = {
              status: true,
              type: "success",
              text: "User has been saved",
            };
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
          })
          .catch((err) => {
            console.log(err.response.data);
            this.loading = false;
            this.sbOptions = {
              status: true,
              type: "error",
              text: "Error saving data",
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
    // updateUserStatus() {
    //   this.loading = true;
    //   let data = {
    //     id: this.usersObj.id,
    //     status: this.newStatus,
    //   };
    //   axios.post("/d/user/status/update", data).then((response) => {
    //     this.$emit("saved", true);
    //     this.sbOptions = {
    //       status: true,
    //       type: "success",
    //       text: "User has been updated",
    //     };
    //   });
    // },
  },
};
</script>