(() => {
    const calendar = document.querySelector('[data-professor-calendar]');
    if (!calendar) return;

    const endpoint = calendar.dataset.endpoint || 'calendario-professor.php';
    const current = new Date();
    const state = {
        year: Number(calendar.dataset.year) || current.getFullYear(),
        month: (Number(calendar.dataset.month) || current.getMonth() + 1) - 1,
        items: [],
        selectedDate: ''
    };
    const monthName = new Intl.DateTimeFormat('pt-BR', { month: 'long', year: 'numeric' });
    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[char]);
    const dateOnly = value => String(value ?? '').slice(0, 10);
    const categoryNames = { evento: 'Evento escolar', feriado: 'Feriado', lembrete: 'Lembrete pessoal' };
    const localDate = date => `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    const labels = {
        month: document.getElementById('prof-calendar-month'),
        grid: document.getElementById('prof-calendar-grid'),
        events: document.getElementById('prof-calendar-events'),
        count: document.getElementById('prof-calendar-count'),
        modal: document.getElementById('prof-reminder-modal')
    };

    function openReminder(date = state.selectedDate) {
        if (!labels.modal) return;
        const targetDate = date || localDate(current);
        const form = labels.modal.querySelector('form');
        form.reset();
        form.querySelector('[name="acao"]').value = 'salvar_lembrete';
        form.querySelector('[name="id_lembrete"]').value = '';
        form.querySelector('[name="titulo"]').value = '';
        form.querySelector('[name="descricao"]').value = '';
        form.querySelector('[name="data_lembrete"]').value = `${targetDate}T09:00`;
        form.querySelector('[name="ano"]').value = targetDate.slice(0, 4);
        form.querySelector('[name="mes"]').value = Number(targetDate.slice(5, 7));
        labels.modal.querySelector('[data-modal-title]').textContent = 'Novo lembrete pessoal';
        labels.modal.querySelector('[type="submit"]').textContent = 'Salvar lembrete';
        labels.modal.showModal();
        form.querySelector('[name="titulo"]').focus();
    }

    function render() {
        const first = new Date(state.year, state.month, 1);
        labels.month.textContent = monthName.format(first);
        document.querySelectorAll('[data-professor-calendar-link]').forEach(link => {
            link.href = `calendario-professor.php?ano=${state.year}&mes=${state.month + 1}`;
        });
        const weekdays = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
        const offset = (first.getDay() + 6) % 7;
        const daysInMonth = new Date(state.year, state.month + 1, 0).getDate();
        const prevMonthDays = new Date(state.year, state.month, 0).getDate();
        let html = weekdays.map(day => `<div class="teacher-calendar-weekday">${day}</div>`).join('');

        for (let index = 0; index < 42; index += 1) {
            let date;
            let outside = false;
            if (index < offset) {
                date = new Date(state.year, state.month - 1, prevMonthDays - offset + index + 1);
                outside = true;
            } else if (index >= offset + daysInMonth) {
                date = new Date(state.year, state.month + 1, index - offset - daysInMonth + 1);
                outside = true;
            } else {
                date = new Date(state.year, state.month, index - offset + 1);
            }
            const key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
            const items = state.items.filter(item => dateOnly(item.data_inicio) === key);
            const today = date.toDateString() === current.toDateString();
            html += `<button type="button" class="teacher-calendar-day${outside ? ' is-outside' : ''}${today ? ' is-today' : ''}${key === state.selectedDate ? ' is-selected' : ''}" data-calendar-date="${key}" aria-label="${date.getDate()} ${new Intl.DateTimeFormat('pt-BR', { month: 'long' }).format(date)}${items.length ? `, ${items.length} evento(s)` : ''}"><span class="teacher-day-number">${date.getDate()}</span><span class="teacher-day-items">${items.slice(0, 2).map(item => `<span class="teacher-day-item ${escapeHtml(item.categoria)}">${escapeHtml(item.titulo)}</span>`).join('')}${items.length > 2 ? `<span class="teacher-day-more">+${items.length - 2}</span>` : ''}</span></button>`;
        }
        labels.grid.innerHTML = html;

        const monthKey = `${state.year}-${String(state.month + 1).padStart(2, '0')}`;
        const monthItems = state.items.filter(item => dateOnly(item.data_inicio).startsWith(monthKey))
            .sort((a, b) => String(a.data_inicio).localeCompare(String(b.data_inicio)));
        labels.count.textContent = `${monthItems.length} ${monthItems.length === 1 ? 'item' : 'itens'}`;
        if (!monthItems.length) {
            labels.events.innerHTML = '<p class="teacher-calendar-empty">Nenhum evento, feriado ou lembrete neste mês.</p>';
            return;
        }
        labels.events.innerHTML = monthItems.map(item => {
            const time = String(item.data_inicio).slice(11, 16);
            const isReminder = item.categoria === 'lembrete';
            const actions = isReminder ? `<div class="teacher-event-actions"><button type="button" data-edit-reminder="${escapeHtml(item.id_lembrete)}">Editar</button><form method="post"><input type="hidden" name="acao" value="excluir_lembrete"><input type="hidden" name="id_lembrete" value="${escapeHtml(item.id_lembrete)}"><input type="hidden" name="ano" value="${state.year}"><input type="hidden" name="mes" value="${state.month + 1}"><button type="submit">Excluir</button></form></div>` : '';
            return `<article class="teacher-event-row ${escapeHtml(item.categoria)}"><time datetime="${escapeHtml(item.data_inicio)}">${dateOnly(item.data_inicio).split('-').reverse().join('/')} ${time}</time><div><span class="teacher-event-kind">${categoryNames[item.categoria] || 'Evento'}</span><strong>${escapeHtml(item.titulo)}</strong>${item.descricao ? `<p>${escapeHtml(item.descricao)}</p>` : ''}</div>${actions}</article>`;
        }).join('');
    }

    async function load() {
        labels.grid.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(`${endpoint}?api=calendario&ano=${state.year}`, { headers: { Accept: 'application/json' } });
            const result = await response.json();
            if (!response.ok || !result.sucesso) throw new Error(result.erro || 'Não foi possível carregar o calendário.');
            state.items = result.dados || [];
            render();
        } catch (error) {
            labels.events.innerHTML = `<p class="teacher-calendar-empty is-error">${escapeHtml(error.message)}</p>`;
        } finally {
            labels.grid.removeAttribute('aria-busy');
        }
    }

    document.getElementById('prof-calendar-prev').addEventListener('click', () => {
        const date = new Date(state.year, state.month - 1, 1);
        state.year = date.getFullYear(); state.month = date.getMonth(); load();
    });
    document.getElementById('prof-calendar-next').addEventListener('click', () => {
        const date = new Date(state.year, state.month + 1, 1);
        state.year = date.getFullYear(); state.month = date.getMonth(); load();
    });
    document.getElementById('prof-calendar-today').addEventListener('click', () => {
        state.year = current.getFullYear(); state.month = current.getMonth();
        state.selectedDate = localDate(current); load();
    });
    document.getElementById('prof-calendar-add-reminder').addEventListener('click', () => openReminder());
    labels.grid.addEventListener('click', event => {
        const day = event.target.closest('[data-calendar-date]');
        if (!day) return;
        state.selectedDate = day.dataset.calendarDate;
        openReminder(state.selectedDate);
        render();
    });
    labels.events.addEventListener('click', event => {
        const button = event.target.closest('[data-edit-reminder]');
        if (!button) return;
        const reminder = state.items.find(item => String(item.id_lembrete) === button.dataset.editReminder);
        if (!reminder || !labels.modal) return;
        const form = labels.modal.querySelector('form');
        form.querySelector('[name="acao"]').value = 'salvar_lembrete';
        form.querySelector('[name="id_lembrete"]').value = reminder.id_lembrete;
        form.querySelector('[name="titulo"]').value = reminder.titulo;
        form.querySelector('[name="descricao"]').value = reminder.descricao || '';
        form.querySelector('[name="data_lembrete"]').value = `${String(reminder.data_inicio).slice(0, 10)}T${String(reminder.data_inicio).slice(11, 16)}`;
        form.querySelector('[name="ano"]').value = String(reminder.data_inicio).slice(0, 4);
        form.querySelector('[name="mes"]').value = Number(String(reminder.data_inicio).slice(5, 7));
        labels.modal.querySelector('[data-modal-title]').textContent = 'Editar lembrete pessoal';
        labels.modal.querySelector('[type="submit"]').textContent = 'Salvar alterações';
        labels.modal.showModal();
    });
    document.querySelectorAll('[data-close-professor-modal]').forEach(button => button.addEventListener('click', () => button.closest('dialog').close()));
    document.querySelectorAll('.teacher-dialog').forEach(dialog => dialog.addEventListener('click', event => {
        if (event.target === dialog) dialog.close();
    }));
    load();
})();
