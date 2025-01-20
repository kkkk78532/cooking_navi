const aiCreateUri = `http://${ipAddress}/cooking_navi/api/ai_create_recipe.php`;
const aiImageCreateUri = `http://${ipAddress}/cooking_navi/api/recipe/ai_image_create.php`;
const saveUri = `http://${ipAddress}/cooking_navi/api/ai_save_recipe.php`;

var keywordList = [];
var recipe = {};

console.log(aiCreateUri)
console.log(aiImageCreateUri)
console.log(saveUri)

// ローディング表示を制御する関数
const showLoading = () => {
    document.getElementById('loading').classList.remove('hidden');
};

const hideLoading = () => {
    document.getElementById('loading').classList.add('hidden');
};

document.addEventListener('DOMContentLoaded', function () {
    const keywordInputContainer = document.getElementById('keywordInputContainer');
    const keywordsInput = document.getElementById('keywordsInput');

    // キーワードを追加する関数
    function addKeyword(keyword) {
        if (keyword.trim() === "") return;

        // キーワードが重複していないか確認
        if (!keywordList.includes(keyword)) {
            keywordList.push(keyword);
            const keywordElement = document.createElement('span');
            keywordElement.classList.add('bg-green-500', 'text-white', 'py-1', 'px-2', 'rounded', 'mr-2', 'mb-2', 'flex', 'items-center');
            keywordElement.innerHTML = `
                ${keyword}
                <button class="ml-2 text-white focus:outline-none remove-keyword">&times;</button>
            `;
            keywordInputContainer.insertBefore(keywordElement, keywordsInput);

            // 削除ボタンのイベントリスナーを追加
            keywordElement.querySelector('.remove-keyword').addEventListener('click', function () {
                keywordInputContainer.removeChild(keywordElement);
                keywordList = keywordList.filter(k => k !== keyword);
            });
        }
        keywordsInput.value = '';
    }

    // Enterキーでキーワードを追加
    keywordsInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addKeyword(keywordsInput.value);
        }
    });
});

const createRecipe = async () => {
    showLoading();

    const genre = document.getElementById('genre').value;
    const time = document.getElementById('time').value;
    const keywords = keywordList.join(',');

    const posts = {
        genre,
        time,
        keywords
    };

    console.log(posts);

    try {
        const response = await fetch(aiCreateUri, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(posts)
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        recipe = await response.json();
        console.log(recipe);
        renderRecipe(recipe);
    } catch (error) {
        console.error('Fetch error:'. error);
    } finally {
        hideLoading();
    }
};

const createImageRecipe = async () => {
    const imageInput = document.getElementById('imageInput');
    const file = imageInput.files[0];

    if (!file) {
        alert("画像を選択してください");
        return;
    }

    const formData = new FormData();
    formData.append('image', file);

    showLoading();
    try {
        const response = await fetch(aiImageCreateUri, {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        renderRecipe(data);
    } catch (error) {
        console.error('Fetch error:', error);
    } finally {
        hideLoading();
    }
};

const renderRecipe = (data) => {
    // レシピデータ一時保存
    recipe = data;

    const recipeDiv = document.getElementById('recipe');
    recipeDiv.innerHTML = '';

    // タイトル、説明、難易度、キーワードを表示
    recipeDiv.innerHTML += `
        <h2 class="text-3xl font-bold text-gray-800 mb-4">${recipe.recipe_title}</h2>
        <p class="text-gray-700 mb-4">${recipe.recipe_introduction}</p>
        <p class="text-gray-600 mb-4"><strong>難易度:</strong> ${recipe.recipe_difficulty}</p>
        <p class="text-gray-600 mb-6"><strong>キーワード:</strong> ${recipe.keywords}</p>
    `;

    // 材料リストを生成
    recipeDiv.innerHTML += `<h3 class="text-2xl font-bold text-gray-800 mb-2">材料</h3>`;
    const ingredientsList = document.createElement('ul');
    ingredientsList.classList.add('list-disc', 'pl-5', 'mb-6');

    recipe.ingredients.forEach(ingredient => {
        const li = document.createElement('li');
        li.innerHTML = `<strong>${ingredient.ingredient_name}</strong>: ${ingredient.quantity}`;
        ingredientsList.appendChild(li);
    });

    recipeDiv.appendChild(ingredientsList);

    // 作り方リストを生成
    recipeDiv.innerHTML += `<h3 class="text-2xl font-bold text-gray-800 mb-2">作り方</h3>`;
    const stepsList = document.createElement('ol');
    stepsList.classList.add('list-decimal', 'pl-5', 'mb-6');

    recipe.recipe_procedure.forEach(step => {
        const li = document.createElement('li');
        li.innerHTML = `ステップ ${step.step_numbers}: ${step.recipe_description}`;
        stepsList.appendChild(li);
    });

    recipeDiv.appendChild(stepsList);
}

const userId = document.getElementById('recipe').getAttribute('data-user-id');

const checkLoginStatus = async () => {
    try {
        const response = await fetch('../api/check_login_status.php');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data.isLoggedIn;
    } catch (error) {
        console.error('Error checking login status:', error);
        return false;
    }
};

const saveRecipe = async () => {
    console.log(recipe);

    // ログイン状態を確認
    const isLoggedIn = await checkLoginStatus();
    if (!isLoggedIn) {
        alert('レシピを保存するにはログインが必要です');
        return;
    }

    if (!recipe.recipe_title || !recipe.ingredients) {
        alert('保存するレシピがありません');
        return;
    }


    showLoading();

    try {
        const response = await fetch(saveUri, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(recipe)
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        // const data = await response.json();
        alert('レシピが正常に保存されました');
        // console.log('Recipe saved successfully:', data);
        // const mealPlan = {
        //     user_id: userId, // 現在のユーザーIDを指定 (仮のID)
        //     plan_date: '2024-12-25', // 計画日を指定 (仮の日付)
        //     meal_type: 'dinner', // 食事の種類 (例: 'breakfast', 'lunch', 'dinner')
        //     recipe_id: data.recipe_id // 保存されたレシピのID
        // };

        // const mealPlanResponse = await fetch("", {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json'
        //     },
        //     body: JSON.stringify(mealPlan)
        // });

        // if (!mealPlanResponse.ok) {
        //     throw new Error('Meal plan save failed');
        // }

        console.log('Meal plan saved successfully');
    } catch (error) {
        console.error('Save error:', error);
        alert('保存エラーが発生しました');
    } finally {
        hideLoading();
    }
}
