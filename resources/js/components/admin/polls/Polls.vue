<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline">Polls</v-toolbar-title>
    </v-app-bar>
    <v-container class="py-8" v-if="pageLoading == true">
      <v-row>
        <v-col cols="12">
          <v-skeleton-loader
            class="mx-auto"
            max-width="100%"
            type="list-item-avatar-three-line, image, article"
          ></v-skeleton-loader>
        </v-col>
      </v-row>
    </v-container>

    <v-container v-else class="mb-3 mx-auto" style="max-width: 1366px">
      <!-- content here -->
      <v-row class="mt-2">
        <v-col cols="12" class="py-0">
          <v-btn to="/d/admin/polls/new" class="secondary mb-5"
            >Create Poll</v-btn
          >
        </v-col>
        <v-col class="col-md-12 mt-1 col-sm-12">
          <v-card class="px-5">
            <v-row>
              <v-col class="col-3 col-sm-3 d-flex">
                <div class="mt-2 mr-2">Show</div>
                <v-autocomplete
                  :items="showRows"
                  v-model="showPerPage"
                  @change="filterRows"
                  dense
                  outlined
                  hide-details
                  label="Entry"
                  class="mt-0 col-md-4 mr-3"
                ></v-autocomplete>
              </v-col>

              <v-spacer></v-spacer>

              <v-col class="col-md-3 col-sm-12">
                <v-text-field
                  v-model="search"
                  append-outer-icon="mdi-magnify"
                  outlined
                  dense
                  clear-icon="mdi-close-circle"
                  clearable
                  label="Search"
                  type="text"
                  hide-details
                  @click:append-outer="searchData"
                  @keydown.enter="searchData"
                  @click:clear="clearSearch('search')"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card>
        </v-col>

        <v-col class="col-md-12 col-sm-12">
          <v-card>
            <v-divider></v-divider>
            <v-simple-table id="page-invoices">
              <template v-slot:default>
                <thead>
                  <tr>
                    <th
                      class="text-left cursor-pointer"
                      @click="OrderByField('id')"
                    >
                      ID
                    </th>
                   
                    <th
                      class="text-left cursor-pointer"
                      @click="OrderByField('title')"
                    >
                      Title
                    </th>
                     <th>
                      Start Date
                    </th>
                     <th>
                      End Date
                    </th>
                    <th
                      class="text-left cursor-pointer"
                      @click="OrderByField('user_id')"
                    >
                      Author
                    </th>
                    <th
                      class="text-left cursor-pointer"
                      @click="OrderByField('created_at')"
                    >
                      Published Date
                    </th>
                    <th
                      class="text-left cursor-pointer"
                      @click="OrderByField('status')"
                    >
                      Status
                    </th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody v-if="items && Object.keys(items).length > 0">
                  <tr v-for="(item, index) in items" :key="index">
                    <td>{{ item.id }}</td>
                    <td>{{ item.title }}</td>
                    <td>{{ item.events && formatDateHelper(item.events.date_start) }}</td>
                    <td>{{ item.events && formatDateHelper(item.events.date_end) }}</td>
                    <td>
                      {{ item.user_id ? item.users.profile.fullname : "" }}
                    </td>
                    <td>{{ formatDateHelper(item.created_at) }}</td>
                    <td>
                      <v-chip
                        small
                        :class="`${
                          item.status == 'active'
                            ? 'success'
                            : item.status == 'disabled'
                            ? 'error'
                            : 'grey'
                        }`"
                        >{{
                          item.status == "active"
                            ? "Active"
                            : item.status == "disabled"
                            ? "Disabled"
                            : "Draft"
                        }}</v-chip
                      >
                    </td>
                    <td>
                      <v-btn
                        fab
                        x-small
                        depressed
                        @click="editData(item)"
                        class="transparent mr-1"
                      >
                        <v-icon small> mdi-pencil </v-icon>
                      </v-btn>
                    </td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
            <div
              v-if="items && Object.keys(items).length == 0"
              class="text-center caption text-capitalize py-3"
            >
              Result Not Found
            </div>
          </v-card>
          <div class="d-flex">
            <div class="col-3 pt-7">Total: {{ totalData }}</div>
            <div class="col-6">
              <v-pagination
                v-if="pageCount > 1"
                class="mt-3"
                v-model="page"
                :length="pageCount"
                @input="onPageChange"
                :total-visible="8"
                :items-per-page="showPerPage"
              ></v-pagination>
            </div>
          </div>
        </v-col>
      </v-row>
    </v-container>
    <dialog-loader :loader-options="loaderOptions"></dialog-loader>
  </div>
</template>

<script>
export default {
  data() {
    return {
      localStorage: localStorage,
      pageLoading: true,
      page: 1,
      pageCount: 0,
      origPageCount: 0,
      loaderOptions: {},
      showRows: [10, 50, 100],
      totalData: 0,
      origTotalData: 0,
      showPerPage: 10,
      origCnt: 0,
      search: "",
      items: [],
      orderBy: [],
      orderByCount: 0,
    };
  },
  methods: {
    OrderByField: function (v) {
      this.orderBy[0] = v;
      if (this.orderByCount % 2) {
        this.orderBy[1] = "DESC";
      } else {
        this.orderBy[1] = "ASC";
      }
      this.orderByCount++;

      this.getAllData(this.page, this.orderBy);
    },
    async getAllData(page, orderby = null) {
      let response = "";
      let sort = "-";
      if (orderby && orderby.length > 0) {
        sort = orderby.toString();
      }

      if (this.search) {
        response = await axios.get(
          "/d/admin/posts-fetch/poll/" +
            this.showPerPage +
            "/" +
            this.search +
            "/" +
            sort +
            "/?page=" +
            page
        );
      } else {
        response = await axios.get(
          "/d/admin/posts-fetch/poll/" +
            this.showPerPage +
            "/-/" +
            sort +
            "/?page=" +
            page
        );
      }

      if (response.data) {
        this.items = Object.assign([], response.data.data);
        this.page = response.data.current_page;
        this.pageCount = response.data.last_page;
        this.totalData = response.data.total;
      }
    },

    searchData: async function () {
      this.loaderOptions = {
        status: true,
        text: "Please wait...",
      };
      if (this.search) {
        this.localStorage.setItem("vpoll", this.search);
      }

      if (this.search && this.search.length > 2) {
        let sort = "-";
        console.log(this.orderBy);
        if (this.orderBy && this.orderBy.length > 0) {
          sort = this.orderBy.toString();
        }

        await axios
          .get(
            "/d/admin/posts-fetch/poll/" +
              this.showPerPage +
              "/" +
              this.search +
              "/" +
              sort
          )
          .then((res) => {
            this.items = res.data.data;

            this.page = res.data.current_page;
            this.pageCount = res.data.last_page;
            this.totalData = res.data.total;
            setTimeout(() => {
              this.loaderOptions.status = false;
            }, 500);
          });
      } else if (!this.search) {
        this.items = this.originalData;
        this.totalData = this.origTotalData;
        this.pageCount = parseInt(this.origPageCount);
        this.loaderOptions.status = false;
      }
    },

    onPageChange: function () {
      this.$router.push("/d/polls/page/" + this.page).catch((err) => {});
    },

    clearSearch: function (v) {
      this.localStorage.setItem("vpoll", "");
      this.search = "";
      if (this.$route.params.page) {
        this.getAllData(this.$route.params.page);
      } else {
        this.getAllData(1);
      }
    },

    filterRows: function () {
      this.loaderOptions = {
        status: true,
        text: "Please wait...",
      };
      this.getAllData(1).then(() => {
        this.loaderOptions = false;
      });
    },
    editData(obj) {
      this.$router.push({
        name: "EditPoll",
        params: { id: obj.id },
      });
    },
  },
  created() {
    this.search = this.localStorage.getItem("vpoll");
    if (this.$route.params.page) {
      this.getAllData(this.$route.params.page).then(() => {
        this.pageLoading = false;
      });
    } else {
      this.getAllData(1).then(() => {
        this.pageLoading = false;
      });
    }
  },
  watch: {
    $route(to, from) {
      this.getAllData(this.$route.params.page, this.orderBy);
    },
  },
};
</script>
