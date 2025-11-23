
const questions = [
    {
        question: "¿Qué tipo de clima prefieres?",
        name: "clima",
        options: [
            { text: "Soleado y cálido (Playa, Verano)", value: "floral" },
            { text: "Fresco y con brisa (Bosque, Montaña)", value: "amaderada" },
            { text: "Templado y acogedor (Otoño, Lluvia)", value: "oriental" },
            { text: "Frío y vigorizante (Invierno, Nieve)", value: "cítrica" }
        ]
    },
    {
        question: "¿Cuál es tu bebida favorita por la noche?",
        name: "bebida",
        options: [
            { text: "Un té o infusión ligera.", value: "cítrica" },
            { text: "Un vino tinto o coñac.", value: "oriental" },
            { text: "Un cóctel fresco y afrutado.", value: "floral" },
            { text: "Un whiskey o ron, con notas ahumadas.", value: "amaderada" }
        ]
    },
    {
        question: "¿Qué material de ropa te resulta más atractivo?",
        name: "ropa",
        options: [
            { text: "Seda o gasa ligera.", value: "floral" },
            { text: "Cuero o gamuza.", value: "amaderada" },
            { text: "Lana o cachemira.", value: "oriental" },
            { text: "Lino o algodón fresco.", value: "cítrica" }
        ]
    }
];

const results = {
    floral: {
        title: "🌸 ¡Tu Fragancia Ideal es **Floral**!",
        description: "Eres una persona **alegre, optimista y romántica**. Las notas de jazmín, rosa y lirio resaltarán tu lado más brillante y dulce. Perfecta para el día a día y ocasiones especiales."
    },
    amaderada: {
        title: "🌲 ¡Tu Fragancia Ideal es **Amaderada**!",
        description: "Eres **elegante, con carácter y misterio**. Te atrae la naturaleza y la solidez. Las notas de sándalo, cedro y vetiver te darán una sensación de profundidad y sofisticación."
    },
    oriental: {
        title: "✨ ¡Tu Fragancia Ideal es **Oriental (Especiada)**!",
        description: "Eres **sensual, atrevido y cálido**. Te gustan los aromas intensos y exóticos. La vainilla, el ámbar, el incienso y las especias crearán un aura envolvente y seductora."
    },
    cítrica: {
        title: "🍋 ¡Tu Fragancia Ideal es **Cítrica (Fresco)**!",
        description: "Eres **dinámico, enérgico y minimalista**. Buscas la frescura y la limpieza. Las notas de limón, bergamota y pomelo te mantendrán revitalizado y ligero."
    }
};

const userScores = { floral: 0, amaderada: 0, oriental: 0, cítrica: 0 };

function startTest() {
    //uso de DOM
    const questionsContainer = document.getElementById('questions-container');
    //uso de DOM
    const startButton = document.getElementById('start-test-btn');
    //uso de DOM
    const submitButton = document.getElementById('submit-test-btn');

    //uso de DOM
    startButton.style.display = 'none';
    //uso de DOM
    submitButton.style.display = 'block';
    
    //uso de DOM
    submitButton.onclick = calculateResult;

    questions.forEach((q, index) => {
        let questionHtml = `<div class="question-block" id="q${index}">
            <p><strong>${index + 1}. ${q.question}</strong></p>`;
        
        q.options.forEach(option => {
            questionHtml += `
                <label>
                    <input type="radio" name="${q.name}" value="${option.value}">
                    ${option.text}
                </label>
            `;
        });
        questionHtml += `</div>`;
        //uso de DOM
        questionsContainer.innerHTML += questionHtml;
    });
}

// 4. Función para calcular y mostrar el resultado
function calculateResult() {
    Object.keys(userScores).forEach(key => userScores[key] = 0);
    //uso de DOM
    const questionsContainer = document.getElementById('questions-container');
    //uso de DOM
    const resultContainer = document.getElementById('result-container');
    
    let allAnswered = true; // (NO es DOM)

    questions.forEach(q => {
        //uso de DOM
        const selectedOption = document.querySelector(`input[name="${q.name}"]:checked`);
        if (selectedOption) {
            //uso de DOM
            const fragranceType = selectedOption.value;
            userScores[fragranceType]++;
        } else {
            allAnswered = false; 
        }
    });

    if (!allAnswered) {
        //uso de DOM
        resultContainer.innerHTML = '<p style="color: red;"><strong>¡Por favor, responde todas las preguntas antes de continuar!</strong></p>';
        return;
    }

    let maxScore = -1;
    let idealFragrance = 'cítrica';
    
    for (const key in userScores) {
        if (userScores[key] > maxScore) {
            maxScore = userScores[key];
            idealFragrance = key;
        }
    }

    const resultData = results[idealFragrance]; // (NO es DOM)
    
    //uso de DOM
    questionsContainer.innerHTML = ''; // Limpiar preguntas
    //uso de DOM
    document.getElementById('submit-test-btn').style.display = 'none'; // Ocultar botón de enviar

    //uso de DOM
    resultContainer.innerHTML = `
        <div style="border: 2px solid #555; padding: 20px; border-radius: 8px;">
            <h3>${resultData.title}</h3>
            <p>${resultData.description}</p>
            <p><strong>Puntuación:</strong> Floral (${userScores.floral}), Amaderada (${userScores.amaderada}), Oriental (${userScores.oriental}), Cítrica (${userScores.cítrica})</p>
            <button onclick="window.location.reload()">Hacer el Test de Nuevo</button>
        </div>
    `;
}