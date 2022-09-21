<template>
  <div>
    <div v-if="userData !== null" class="d-flex align-center">
      <v-avatar size="40" color="primary" class="mr-2">
        <v-img
          width="30"
          height="30"
          :aspect-ratio="1"
          class="ma-1"
          v-if="isAdmin === true"
          :src="$baseUrl + '/images/fav.png'"
        ></v-img>
        <div v-else class="text-h5">
          {{ userData ? printInitials(userData.profile.fullname) : "" }}
        </div>
      </v-avatar>
      <div class="d-flex flex-column">
        <div class="text-h6 line-height-1em">
          {{ isAdmin === true ? "GAG" : userData.profile.fullname }}
        </div>
        <div v-if="meta.status == true" class="line-height-1em">
          <small>
            {{ formatDateHelper(meta.post_date) }}
          </small>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: null,
    },
    meta: {
      type: Object,
      default: () => ({
        status: false,
        post_date: "date",
      }),
    },
  },
  data() {
    return {
      isAdminArray: ["superadmin", "admin", "moderator"],
      userData: this.user ? this.user : null,
    };
  },
  computed: {
    isAdmin() {
      return this.isAdminArray.includes(this.userData.role);
    },
  },
};
</script>
