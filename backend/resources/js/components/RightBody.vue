<template>
  <div id="right">
    <h1>Development CRM</h1>
    <div class="horizontal">
      <img src="images/horizontal.png" />
    </div>

    <p>
      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
      Lorem Ipsum has been the industry's standard dummy text ever since the
      1500s
    </p>

    <div class="users-icon"><img src="images/users.png" /></div>

    <div class="tasks">
      <div class="add-tasks">
        <h2>Daily's Task</h2>
      </div>

      <ul class="tasks-list">
        <li v-for="item_task in v_dailytasks" :key="item_task.id">
          <div class="info">
            <div class="left">
              <label class="myCheckbox">
                <input
                  type="checkbox"
                  name="test"
                  :checked="item_task.approved"
                  @change="onUpdateDailyTask(item_task)"
                />
                <span></span>
              </label>

              <h4>{{ item_task.title }}</h4>
            </div>
            <div class="right">
              <img src="images/edit.png" />
              <img src="images/del.png" @click="onDeleteDailyTask(item_task)" />
              <button
                :class="{
                  inprogress: !item_task.approved,
                  approved: item_task.approved,
                }"
              >
                {{ item_task.approved ? "Approved" : "In progress" }}
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <div class="upcoming">
      <div class="add-tasks">
        <h2>Upcoming</h2>
        <div class="add-action">
          <img src="images/add.png" alt="" @click="onAddUpcomingTask" />
        </div>
      </div>

      <form action="" @submit="onAddUpcomingTask">
        <input type="text" v-model="v_newtask" />
      </form>
      <ul class="tasks-list">
        <li
          v-for="item_upcoming_task in v_upcomings"
          :key="item_upcoming_task.id"
        >
          <div class="info">
            <div class="left">
              <label class="myCheckbox">
                <input
                  type="checkbox"
                  name="test"
                  :checked="item_upcoming_task.approved"
                  @change="onCheckUpcoming(item_upcoming_task.taskId)"
                />
                <span></span>
              </label>
              <h4>{{ item_upcoming_task.title }}</h4>
            </div>

            <div class="right">
              <img src="images/edit.png" alt="" />
              <img
                src="images/del.png"
                alt=""
                @click="onDelUpcoming(item_upcoming_task)"
              />
              <button
                :class="{
                  waiting: item_upcoming_task.waiting,
                }"
              >
                Waiting
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      v_dailytasks: [],
      v_upcomings: [],
      v_newtask: "",
    };
  },
  created() {
    this.onFetchDailyTasks();
    this.onFetchUpcoming();
  },
  methods: {
    // ~~~~~ upcoming
    onCheckUpcoming(arg_task_id) {
      if (this.v_dailytasks.length > 4) {
        alert("Please complete the upcoming task!!!");
        window.location.href = "/";
      } else {
        this.onAddDailyTask(arg_task_id);
        this.onDelSubFunc(arg_task_id, "upcoming");
        this.onFetchUpcoming();
      }
    },
    //U-1
    onFetchUpcoming() {
      this.onFetchSubFunc("upcoming");
    },
    //U-2
    onAddUpcomingTask(e) {
      e.preventDefault();
      if (this.v_newtask.trim().length === 0) {
        alert("Please input the upcoming task name!!!");
      } else {
        if (this.v_upcomings.length > 4) {
          alert("Please complete the upcoming task!!!");
        } else {
          const constNewTask = {
            title: this.v_newtask,
            waiting: true,
            taskId: Math.random().toString(36).substring(7),
          };
          fetch("/api/upcoming", {
            method: "POST",
            headers: {
              "content-type": "application/json",
            },
            body: JSON.stringify(constNewTask),
          }).then(() => this.v_upcomings.push(constNewTask));
          this.v_newtask = "";
        }
      }
    },
    //U-3
    onDelUpcoming(arg_task) {
      if (confirm("Are you sure to delete this task?: " + arg_task.title)) {
        this.onDelSubFunc(arg_task.taskId, "upcoming");
      }
    },

    // ~~~~~ daily
    //D-1
    onFetchDailyTasks() {
      this.onFetchSubFunc("dailytask");
    },
    //D-2
    onAddDailyTask(arg_task_id) {
      const task_tmp = this.v_upcomings.filter(
        ({ taskId: id }) => id === arg_task_id
      )[0];
      fetch("/api/dailytask", {
        method: "POST",
        headers: {
          "content-type": "application/json",
        },
        body: JSON.stringify(task_tmp),
      }).then(() => this.v_dailytasks.unshift(task_tmp));
    },
    //D-3
    onDeleteDailyTask(arg_task) {
      if (confirm("Are you sure to delete this task?: " + arg_task.title)) {
        this.onDelSubFunc(arg_task.taskId, "dailytask");
      }
    },
    //D-4
    onUpdateDailyTask(arg_task) {
      fetch(`/api/dailytask/${arg_task.taskId}`, {
        method: "PUT",
        headers: {
          "content-type": "application/json",
        },
        body: JSON.stringify(arg_task),
      })
        .then(() => {
          this.onFetchDailyTasks();
        })
        .catch((err) => alert("UPD daily task ERR!!! " + err));
    },

    // ~~~~~ sub function
    onFetchSubFunc(arg_uri_name) {
      fetch("/api/" + arg_uri_name)
        .then((res) => res.json())
        .then(({ data }) => {
          if (arg_uri_name === "upcoming") {
            this.v_upcomings = data;
          } else {
            this.v_dailytasks = data;
          }
        })
        .catch((err) => alert("SEL " + arg_uri_name + " tasks ERR!!! " + err));
    },
    onDelSubFunc(arg_task_id, arg_uri_name) {
      fetch(`/api/${arg_uri_name}/${arg_task_id}`, {
        method: "DELETE",
      })
        .then(() => {
          if (arg_uri_name === "upcoming") {
            this.v_upcomings = this.v_upcomings.filter(
              ({ taskId: id }) => id !== arg_task_id
            );
          } else {
            this.v_dailytasks = this.v_dailytasks.filter(
              ({ taskId: id }) => id !== arg_task_id
            );
          }
        })
        .catch((err) => alert("DEL " + arg_uri_name + " task ERR!!! " + err));
    },
  },
};
</script>

<style>
</style>