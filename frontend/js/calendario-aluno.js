(() => {
    const annualPage = document.getElementById('area-calendario-aluno');
    const dashboard = document.getElementById('calendario');
    if (!annualPage && !dashboard) return;

    const monthName = (year, month) => new Intl.DateTimeFormat('pt-BR', {month:'long', year:'numeric'}).format(new Date(year, month - 1, 1));
    const dateKey = value => String(value || '').slice(0, 10);
    const localDate = key => {
        const [year, month, day] = key.split('-').map(Number);
        return new Date(year, month - 1, day);
    };
    const formatDate = key => new Intl.DateTimeFormat('pt-BR', {day:'2-digit', month:'long'}).format(localDate(key));
    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
    const eventsInMonth = (events, year, month) => events.filter(event => {
        const key = dateKey(event.data_inicio);
        return key.startsWith(`${year}-${String(month).padStart(2, '0')}-`);
    });

    function eventMarkup(event) {
        const key = dateKey(event.data_inicio);
        const time = String(event.data_inicio || '').slice(11, 16);
        const hasTime = time && time !== '00:00';
        return `<li class="calendar-event${Number(event.is_feriado) ? ' holiday-event' : ''}">
            <time datetime="${escapeHtml(event.data_inicio)}">${escapeHtml(formatDate(key))}${hasTime ? ` · ${escapeHtml(time)}` : ''}</time>
            <div><strong>${escapeHtml(event.titulo)}</strong>${event.descricao ? `<p>${escapeHtml(event.descricao)}</p>` : ''}${event.local ? `<small>${escapeHtml(event.local)}</small>` : ''}</div>
        </li>`;
    }

    function renderDayGrid(body, year, month, events, compact = false) {
        if (!body) return;
        const firstWeekday = new Date(year, month - 1, 1).getDay();
        const totalDays = new Date(year, month, 0).getDate();
        const eventsByDay = new Map();
        eventsInMonth(events, year, month).forEach(event => {
            const day = Number(dateKey(event.data_inicio).slice(8, 10));
            if (!eventsByDay.has(day)) eventsByDay.set(day, []);
            eventsByDay.get(day).push(event);
        });
        let html = '';
        for (let cell = 0; cell < 42; cell += 1) {
            if (cell % 7 === 0) html += '<tr>';
            const day = cell - firstWeekday + 1;
            if (day < 1 || day > totalDays) html += '<td class="empty" aria-hidden="true"></td>';
            else {
                const dayEvents = eventsByDay.get(day) || [];
                const key = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const classes = [dayEvents.length ? 'has-events' : '', dayEvents.some(event => Number(event.is_feriado)) ? 'has-holiday' : ''].filter(Boolean).join(' ');
                const badges = compact ? dayEvents.slice(0, 2).map(event => `<span class="event-pill" title="${escapeHtml(event.titulo)}">${escapeHtml(event.titulo)}</span>`).join('') : (dayEvents.length ? `<span class="day-event-count">${dayEvents.length} evento${dayEvents.length === 1 ? '' : 's'}</span>` : '');
                html += `<td class="${classes}"><time datetime="${key}">${day}</time>${badges}</td>`;
            }
            if (cell % 7 === 6) html += '</tr>';
        }
        body.innerHTML = html;
    }

    function renderList(element, events, emptyMessage) {
        if (!element) return;
        element.innerHTML = events.length ? events.map(eventMarkup).join('') : `<li class="calendar-empty">${escapeHtml(emptyMessage)}</li>`;
    }

    function renderAnnual(events, year) {
        document.querySelectorAll('.month-card[data-mes]').forEach(card => {
            const monthEvents = eventsInMonth(events, year, Number(card.dataset.mes));
            const count = card.querySelector('.month-event-count');
            if (count) count.textContent = monthEvents.length ? `${monthEvents.length} evento${monthEvents.length === 1 ? '' : 's'}` : 'Sem eventos';
        });
        const today = new Date();
        const firstUpcomingDay = year === today.getFullYear() ? `${year}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}` : `${year}-01-01`;
        const upcoming = events.filter(event => dateKey(event.data_inicio) >= firstUpcomingDay).slice(0, 10);
        renderList(document.getElementById('eventos-proximos-anual'), upcoming, 'Nenhum evento cadastrado neste ano.');
    }

    function renderDashboard(events) {
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth() + 1;
        const monthEvents = eventsInMonth(events, year, month);
        const label = document.getElementById('dashboard-month-label');
        if (label) label.textContent = monthName(year, month);
        renderDayGrid(document.getElementById('dashboard-calendar-days'), year, month, events, true);
        renderList(document.getElementById('dashboard-month-events'), monthEvents, 'Nenhum evento cadastrado para este mes.');

        const todayKey = `${year}-${String(month).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
        const next = events.find(event => dateKey(event.data_inicio) >= todayKey);
        const daysElement = document.getElementById('prox-evento-dias');
        const titleElement = document.getElementById('prox-evento-titulo');
        if (daysElement) daysElement.textContent = next ? Math.max(0, Math.ceil((localDate(dateKey(next.data_inicio)) - new Date(year, month - 1, now.getDate())) / 86400000)) : '—';
        if (titleElement) titleElement.textContent = next ? next.titulo : 'Nenhum evento futuro';
    }

    async function loadEvents() {
        const year = Number(annualPage?.dataset.ano || new Date().getFullYear());
        const error = document.getElementById('calendar-error');
        try {
            const response = await fetch(`calendario-aluno.php?api=eventos&ano=${year}`, {headers:{'Accept':'application/json'}, cache:'no-store'});
            const payload = await response.json();
            if (!response.ok || !payload.sucesso) throw new Error(payload.erro || 'Falha ao carregar a agenda.');
            const events = (payload.dados || []).sort((a, b) => String(a.data_inicio).localeCompare(String(b.data_inicio)));
            if (annualPage) {
                const selectedMonth = Number(annualPage.dataset.mes || 0);
                if (selectedMonth) {
                    renderDayGrid(document.getElementById('student-month-days'), year, selectedMonth, events);
                    renderList(document.getElementById('eventos-do-mes'), eventsInMonth(events, year, selectedMonth), 'Nenhum evento para este mes.');
                } else renderAnnual(events, year);
            }
            if (dashboard) renderDashboard(events);
            if (error) error.hidden = true;
        } catch (exception) {
            if (error) { error.textContent = exception.message; error.hidden = false; }
            renderList(document.getElementById('eventos-do-mes'), [], 'Nao foi possivel carregar os eventos.');
            renderList(document.getElementById('eventos-proximos-anual'), [], 'Nao foi possivel carregar os eventos.');
            renderList(document.getElementById('dashboard-month-events'), [], 'Nao foi possivel carregar os eventos.');
        }
    }

    loadEvents();
    window.setInterval(loadEvents, 60000);
})();
