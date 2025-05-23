document.addEventListener("DOMContentLoaded", function() {
    let mainWords;
    let currentMainWord;
    let usedWords = [];

    fetch('words.json')
        .then(response => response.json())
        .then(data => {
            mainWords = data['главные слова'];
            loadRandomMainWord();
        });

    const mainWordContainer = document.getElementById('main-word');
    const inputWord = document.getElementById('input-word');
    const submitWord = document.getElementById('submit-word');
    const wordsList = document.getElementById('words-list');
    const modal = document.getElementById('modal');
    const closeModal = document.getElementById('close-modal');
    const congratsModal = document.getElementById('congrats-modal');
    const closeCongratsModal = document.getElementById('close-congrats-modal');

    submitWord.addEventListener('click', checkWord);
    inputWord.addEventListener('input', hideModal);
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    closeCongratsModal.addEventListener('click', () => {
        congratsModal.style.display = 'none';
        loadRandomMainWord();
    });
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    congratsModal.addEventListener('click', (event) => {
        if (event.target === congratsModal) {
            congratsModal.style.display = 'none';
            loadRandomMainWord();
        }
    });

    function loadRandomMainWord() {
        if (mainWords.length > 0) {
            currentMainWord = mainWords[Math.floor(Math.random() * mainWords.length)];
            mainWordContainer.textContent = currentMainWord['слово'];
            wordsList.innerHTML = '';
            usedWords = [];
        } else {
            mainWordContainer.textContent = 'Нет доступных слов!';
            inputWord.disabled = true;
            submitWord.disabled = true;
        }
    }

    function checkWord() {
        const word = normalizeInput(inputWord.value.trim().toLowerCase());
        if (currentMainWord['подслова'].map(normalizeInput).includes(word) && !usedWords.includes(word)) {
            const wordElement = document.createElement('div');
            wordElement.textContent = word;
            wordsList.appendChild(wordElement);
            usedWords.push(word);
            inputWord.value = '';
            if (usedWords.length === currentMainWord['подслова'].length) {
                showCongratsModal();
                setTimeout(() => {
                    congratsModal.style.display = 'none';
                    loadRandomMainWord();
                }, 3000); // After 3 seconds, move to the next word
            }
        } else {
            modal.style.display = 'flex';
        }
    }

    function normalizeInput(input) {
        return input.replace(/[1I|]/g, 'Ӏ'); // Replace '1', 'I', and Unicode 04C0 with '|'
    }

    function showCongratsModal() {
        congratsModal.style.display = 'flex';
    }

    function hideModal() {
        modal.style.display = 'none';
    }

    window.addEventListener('beforeunload', function (e) {
        e.preventDefault();
        e.returnValue = '';
        const confirmReset = confirm("Вы действительно хотите сбросить слово?");
        if (confirmReset) {
            loadRandomMainWord();
        }
    });


});
