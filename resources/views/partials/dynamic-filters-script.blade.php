@php
    $positionsByModality = config('positions.by_modality');
    $selectedPosition = $selectedPosition ?? '';
    $selectedCity = $selectedCity ?? '';
    $requireModality = $requireModality ?? false;
    $positionEmptyLabel = $positionEmptyLabel ?? 'Todas';
    $cityEmptyLabel = $cityEmptyLabel ?? 'Todas as cidades';
@endphp

<script>
(function () {
    const positionsByModality = @json($positionsByModality);
    const modalityId = @json($modalityId ?? 'modality');
    const modalityName = @json($modalityName ?? 'modality');
    const modalityEl = document.getElementById(modalityId);
    const modalityRadios = document.querySelectorAll(`input[type="radio"][name="${modalityName}"]`);
    const positionEl = document.getElementById(@json($positionId ?? 'position'));
    const stateEl = document.getElementById(@json($stateId ?? 'state'));
    let cityEl = document.getElementById(@json($cityId ?? 'city'));
    let selectedPosition = @json($selectedPosition);
    let selectedCity = @json($selectedCity);
    const requireModality = @json($requireModality);
    const positionEmptyLabel = @json($positionEmptyLabel);
    const cityEmptyLabel = @json($cityEmptyLabel);
    const cityCache = {};

    if (!positionEl) return;

    function currentModality() {
        if (modalityEl && modalityEl.tagName === 'SELECT') {
            return modalityEl.value;
        }

        const checked = Array.from(modalityRadios).find((radio) => radio.checked);
        return checked ? checked.value : '';
    }

    function ensureSelect(el) {
        if (!el || el.tagName === 'SELECT') {
            return el;
        }

        const select = document.createElement('select');
        select.id = el.id;
        select.name = el.name;
        select.className = el.className || 'form-select form-select-sm';
        el.replaceWith(select);
        return select;
    }

    function fillSelect(select, options, placeholder, selected) {
        select.innerHTML = '';

        const empty = document.createElement('option');
        empty.value = '';
        empty.textContent = placeholder;
        select.appendChild(empty);

        options.forEach((value) => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = value;
            if (selected && selected === value) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }

    function allPositions() {
        const seen = new Set();
        const list = [];

        Object.values(positionsByModality).forEach((group) => {
            group.forEach((position) => {
                if (!seen.has(position)) {
                    seen.add(position);
                    list.push(position);
                }
            });
        });

        return list;
    }

    function updatePositions(preserveSelection) {
        const modality = currentModality();
        const positions = positionsByModality[modality] || [];
        const keep = preserveSelection ? selectedPosition : '';

        if (!modality && requireModality) {
            fillSelect(positionEl, [], 'Selecione a modalidade', '');
            positionEl.disabled = true;
            selectedPosition = '';
            return;
        }

        positionEl.disabled = false;
        const options = modality ? positions : allPositions();
        const validKeep = keep && options.includes(keep) ? keep : '';
        fillSelect(positionEl, options, positionEmptyLabel, validKeep);
        selectedPosition = validKeep;
    }

    async function loadCities(preserveSelection) {
        cityEl = ensureSelect(cityEl);
        if (!stateEl || !cityEl) return;

        const uf = stateEl.value;
        const keep = preserveSelection ? (selectedCity || '') : '';

        if (!uf) {
            fillSelect(cityEl, keep ? [keep] : [], 'Selecione o estado', keep);
            cityEl.disabled = true;
            return;
        }

        cityEl.disabled = true;
        fillSelect(cityEl, keep ? [keep] : [], 'Carregando cidades...', keep);

        try {
            let cities = cityCache[uf];

            if (!cities) {
                const response = await fetch(
                    `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${encodeURIComponent(uf)}/municipios?orderBy=nome`
                );

                if (!response.ok) {
                    throw new Error('Falha ao carregar cidades');
                }

                const data = await response.json();
                cities = data.map((item) => item.nome);
                cityCache[uf] = cities;
            }

            fillSelect(cityEl, cities, cityEmptyLabel, keep);
            cityEl.disabled = false;
        } catch (error) {
            console.error(error);
            fillSelect(cityEl, keep ? [keep] : [], 'Não foi possível carregar as cidades', keep);
            cityEl.disabled = false;
        }
    }

    cityEl = ensureSelect(cityEl);

    if (modalityEl && modalityEl.tagName === 'SELECT') {
        modalityEl.addEventListener('change', function () {
            updatePositions(false);
        });
    }

    modalityRadios.forEach((radio) => {
        radio.addEventListener('change', function () {
            updatePositions(false);
        });
    });

    if (stateEl) {
        stateEl.addEventListener('change', function () {
            selectedCity = '';
            loadCities(false);
        });
    }

    updatePositions(true);
    loadCities(true);
})();
</script>
