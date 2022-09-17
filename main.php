<?php

class Farm {
    private $animals;
    private $production;

    function __construct() {
        $this->animals = [];
        $this->production = [];
    }
    // Добавляем животного в хлев. Регистрационный номер животного - индекс массива
    public function addAnimal(Animal $animal) {
        $this->animals[] = $animal;
    }
    // Создаем продукцию от каждого животного в хлеву и заносим ее в хлев
    public function createProduction() {
        foreach($this->animals as $animal) {
            $this->production = array_merge($this->production, $animal->createProduction());
        }
    }
    // Считаем количество животных каждого типа и выводим
    public function printAnimalTypesInfo() {
        $animalCountByTypes = [];
        foreach($this->animals as $animal) {
            // Если еще не считали животных такого типа, ставим счетчик
            if (!array_key_exists($animal->getType(), $animalCountByTypes)) {
                $animalCountByTypes[$animal->getType()] = 0;
            }
            $animalCountByTypes[$animal->getType()]++;
        }
        print("Количество каждого животного:\r\n");
        foreach($animalCountByTypes as $animalType => $count) {
            print(chr(9)."$animalType: $count\r\n");
        };
    }
    // Считаем накопившуюся продукцию и выводим
    public function printCollectedProductionInfo() {
        $foodAmmountByTypes = [];
        foreach($this->production as $food) {
            // Если еще не считали такой тип продукции, ставим на него счетчик и выясняем меру
            if (!array_key_exists($food->getType(), $foodAmmountByTypes)) {
                $foodAmmountByTypes[$food->getType()] = 
                [ 
                    'measure' => $food->getMeasure(), 
                    'count' => 0
                ];
            }
            $foodAmmountByTypes[$food->getType()]['count']++;
        }
        print("Произведенная продукция:\r\n");
        foreach($foodAmmountByTypes as $foodType => $info) {
            print(chr(9)."$foodType: ${info['count']} ${info['measure']}\r\n");
        };
    }
    // Сбрасываем произведенную продукцию
    public function flushProduction() {
        $this->production = [];
    }
}

// Каждое животное должно иметь возможность вернуть свой тип и создавать продукцию
abstract class Animal {
    abstract public function getType();
    abstract public function createProduction();
}

class Cow extends Animal {
    public function getType() {
        return 'Корова';
    }
    // Может создавать от 8 до 12 литров молока
    public function createProduction() {
        $milk = [];
        for($i = 1; $i <= rand(8, 12); $i++) {
            $milk[] = new Milk();
        }
        return $milk;
    }    
}

class Chicken extends Animal {
    public function getType() {
        return 'Курица';
    }
    public function createProduction() {
        $eggs = [];
        // Может создать или не создать яйцо
        if (rand(0, 1)) {
            $eggs[] = new Egg();
        }
        return $eggs;
    }
}

// Каждый вид продукции должен иметь возможность вернуть свой вид и меру (в чем измеряется эта продукция)
abstract class Food {
    abstract function getType();
    abstract function getMeasure();
}

class Milk extends Food {
    function getType() {
        return 'Молоко';
    }
    function getMeasure() {
        return 'Литр';
    }
} 

class Egg extends Food {
    function getType() {
        return 'Яйцо';
    }
    function getMeasure() {
        return 'Штука';
    }
}

// Создаем хлев
$farm = new Farm();
// Создаем в хлеву 10 коров
for($i = 1; $i <= 10; $i++) {
    $farm->addAnimal(new Cow());
}
// Создаем в хлеву 20 куриц
for($i = 1; $i <= 20; $i++) {
    $farm->addAnimal(new Chicken());
}
// Выводим количество каждого животного
$farm->printAnimalTypesInfo();
// Создаем 7 раз продукцию от каждого животного
for($i = 1; $i <= 7; $i++) {
    $farm->createProduction();
}
// Выводим количество созданной продукции каждого типа
$farm->printCollectedProductionInfo();
// Сбрасываем всю произведенную продукцию (чтобы она не входила в следующий подсчет)
$farm->flushProduction();
// Создаем 5 куриц в хлеву
for($i = 1; $i <= 5; $i++) {
    $farm->addAnimal(new Chicken());
}
// Создаем 1 корову в хлеву
$farm->addAnimal(new Cow());
// Выводим количество каждого животного
$farm->printAnimalTypesInfo();
// Создаем 7 раз продукцию от каждого животного
for($i = 1; $i <= 7; $i++) {
    $farm->createProduction();
}
// Выводим количество созданной продукции каждого типа
$farm->printCollectedProductionInfo();
?>