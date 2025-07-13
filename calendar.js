const calendarEl = document.getElementById("calendar");
const monthYearEl = document.getElementById("monthYear");
const modalEl = document.getElementById("eventModal");
let currentDate = new Date();

function renderCalendar(date = newDate()) {
    calendarEl.innerHTML = '';

    const today = new Date();
    const month = date.getMonth();
    const year = date.getFullYear();

    const totalDays = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1).getDay();

    //display month and year
    monthYearEl.textContent = date.toLocaleDateString("en-US", {
        month: 'long',
        year: 'numeric'
    });

    const weekDays = ["L", "M", "X", "J", "V", "S", "D"];
    weekDays.forEach(day => {
        const dayEl = document.createElement("div");
        dayEl.className = "day-name";
        dayEl.textContent = day;
        calendarEl.appendChild(dayEl);
    });

    for (let i = 0; i < firstDayOfMonth; i++) {
        calendarEl.appendChild(document.createElement("div"));
    }

    //loop through days
    for (let day = 1; day <= totalDays; day++) {
        const dateStr = `${String(day).padStart(2, '0')}-${String(month + 1).padStart(2, '0')}-${year}`;
        const cell = document.createElement("div");
        cell.className = "day";

        if (day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
        ) {
            cell.classList.add('today');
        }

        const dateEl = document.createElement("div");

        dateEl.className = "date-number";
        dateEl.textContent = day;
        cell.appendChild(dateEl);

        const eventToday = events.filter(e => e.date === dateStr);
        const eventBox = document.createElement("div");
        eventBox.className = "events";

        //render events
        eventToday.forEach(events => {
            const ev = document.createElement("div");
            ev.className = "appointment";

            const eventEl = document.createElement("div");
            eventEl.className = "event";
            eventEl.textContent = events.title.split(" - ")[0];

            const instructorEl = document.createElement("div");
            instructorEl.className = "instructor";
            instructorEl.textContent = events.title.split(" - ")[1];

            const timeEl = document.createElement("div");
            timeEl.className = "time";
            timeEl.textContent = `${events.start_time} - ${events.end_time}`;

            ev.appendChild(courseEl);
            ev.appendChild(instructorEl);
            ev.appendChild(timeEl);
            eventBox.appendChild(ev);
        });

        //overlay buttons
        const overlay = document.createElement("div");
        overlay.className = "day-overlay";

        const addBtn = document.createElement("button");
        addBtn.className = "overlay-btn";
        addBtn.textContent = "Add";
        addBtn.onclick = (e) => {
            e.stopPropagation();
            openModalForAdd(dateStr);
        };
        overlay.appendChild(addBtn);

        if (eventToday.length > 0) {
            const editBtn = document.createElement("button");
            editBtn.className = "overlay-btn";
            editBtn.textContent = "Edit";
            editBtn.onclick = (e) => {
                e.stopPropagation();
                openModalForEdit(eventToday);
            };
            overlay.appendChild(editBtn);
        }

        cell.appendChild(overlay);
        cell.appendChild(eventBox);
        calendarEl.appendChild(cell);
    }
}

//add event modal
function openModalForAdd(dateStr) {
    document.getElementById("formAction").value = "add";
    document.getElementById("eventId").value = "";
    document.getElementById("deleteEventId").value = "";
    document.getElementById("eventName").value = "";
    document.getElementById("instructorName").value = "";
    document.getElementById("startDate").value = dateStr;
    document.getElementById("endDate").value = dateStr;
    document.getElementById("startTime").value = "08:00";
    document.getElementById("endTime").value = "09:00";
    document.getElementById("descriptionEvent").value = "";

    const selector = document.getElementById("eventSelector");
    const wrapper = document.getElementById("eventSelectorWrapper");
    if (selector && wrapper) {
        selector.innerHTML = "";
        wrapper.style.display = "none";
    }

    modalEl.style.display = "flex";
}

//edit event modal
function openModalForEdit(eventsOnDate) {
    document.getElementById("formAction").value = "edit";
    modalEl.style.display = "flex";

    const selector = document.getElementById("eventSelector");
    const wrapper = document.getElementById("eventSelectorWrapper");

    selector.innerHTML = "<option disabled selected>Choose event...</option>";

    eventsOnDate.forEach((e) => {
        const option = document.createElement("option");
        option.value = JSON.stringify(e);
        option.textContent = `${e.title} (${e.start} to ${e.end})`;
        selector.appendChild(option);
    });

    if (eventsOnDate.length > 1) {
        wrapper.style.display = "block";
    } else {
        wrapper.style.display = "none";
    }

    handleEventSelection(JSON.stringify(eventsOnDate[0]));
}

function handleEventSelection(eventJSON) {
    const ev = JSON.parse(eventJSON);

    document.getElementById("eventId").value = ev.id;
    document.getElementById("deleteEventId").value = ev.id;

    const [event, instructor] = ev.title.split(" - ").map((e) => e.trim());

    document.getElementById("eventName").value = event || "";
    document.getElementById("instructorName").value = instructor || "";
    document.getElementById("startDate").value = ev.start || "";
    document.getElementById("endDate").value = ev.end || "";
    document.getElementById("startTime").value = ev.start_time || "";
    document.getElementById("endTime").value = ev.end_time || "";
    document.getElementById("descriptionEvent").value = ev.description || "";
}

function closeModal() {
    modalEl.style.display = "none";
}

function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    renderCalendar(currentDate);
}

function updateClock() { //hh:mm:ss
    const now = new Date();
    const clock = document.getElementById("clk");
    clock.textContent = [
        now.getHours().toString().padStart(2, "0"),
        now.getMinutes().toString().padStart(2, "0"),
        now.getSeconds().toString().padStart(2, "0"),
    ].join(":");
}

renderCalendar(currentDate);
updateClock();
setInterval(updateClock, 1000);