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
        <h2>Today's Task</h2>
        <div class="add-action"><img src="images/add.png" /></div>
      </div>

      <ul class="tasks-list">
        <!-- <li v-for="task in v_todaytasks" v-bind:key="task.id">
          <div class="info">
            <div class="left">
              <label class="myCheckbox">
                <input
                  type="checkbox"
                  name="test"
                  :checked="task.completed"
                  @change="updateTodayTask(task.taskId)"
                />
                <span></span>
              </label>

              <h4>{{ task.title }}</h4>
            </div>
            <div class="right">
              <img src="images/edit.png" />
              <img src="images/del.png" @click="deleteTask(task.taskId)" />

              <button
                v-bind:class="{
                  inprogress: !task.approved,
                  approved: task.approved,
                }"
              >
                {{ task.approved ? "Approved" : "In progress" }}
              </button>
            </div>
          </div>
        </li> -->
      </ul>
    </div>

    <div class="upcoming">
      <div class="add-tasks">
        <h2>Upcoming</h2>
        <div class="add-action">
          <img src="images/add.png" alt="" />
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
                  :checked="item_upcoming_task.done"
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
                @click="onDelUpcoming(item_upcoming_task.taskId)"
              />
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
      v_todaytasks: [],
      v_upcomings: [],
      v_newtask: "",
    };
  },
  created() {
    this.onFetchTodayTasks();
    this.onFetchUpcoming();
  },
  methods: {
    onFetchUpcoming() {
      fetch("/api/upcoming")
        .then((res) => res.json())
        .then(({ data }) => {
          this.v_upcomings = data;
        })
        .catch((err) => alert("SEL upcoming tasks ERR!!! " + err));
    },
    onAddUpcomingTask(e) {
      e.preventDefault();
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
    },
    onFetchTodayTasks() {},
    onDelUpcoming(arg_task_id) {
      if (confirm("Are you sure?")) {
        fetch(`/api/upcoming/${arg_task_id}`, {
          method: "DELETE",
        })
          .then((res) => res.json())
          .then(() => {
            this.v_upcomings = this.v_upcomings.filter(
              ({ taskId: id }) => id !== arg_task_id
            );
          })
          .catch((err) => alert("DEL upcoming tasks ERR!!! " + err));
      }
    },
  },
};
</script>

<style>
</style>