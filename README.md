![GitHub last commit](https://img.shields.io/github/last-commit/ValentinNikolaev/elevator)
![visitors](https://visitor-badge.laobi.icu/badge?page_id=ValentinNikolaev.elevator)

### Особенности
- поддерживает любое количество пользователей. Пользователи могут заходить в лифт на разных этажах и хотеть ехать в разные направления
- диаграмма на картинке местами не соответствует коду. Я уже столько раз его переписал, что просто ломает все переделывать
- ридми пока на ру
- supervisor конфиг пока отключен (есть кое-какие вопросы)
- тестов нет, добавлю в перспективен. Все на контейнерах, будет изи.
- местами нарушается закон Деметры и есть вопросы к выделенным сущностям (доменам)

### About
Этот алгоритм составлен с учетом работы системы с собирательным управлением вниз, т.е. выполняются попутные вызовы при движении кабины вниз.
Таким образом, в подпрограмме реализуются:
- ожидание и регистрация вызова
- проверка нахождения кабины лифта на этаже вызова

В зависимости от этого осуществляется:
- открытие дверей кабины с последующей работой лифта по приказу 
- проверяется условие занятости кабины 

Если кабина свободна, то осуществляется выбор направления движения кабины. 
В зависимости от этого после получения приказа выполняются попутные вызовы при движении вниз (если они зарегистрированы) или движение кабины на наивысший из этажей, с которых поступили вызовы. 
Затем, после получения приказа, собирательное управление для движения вниз.

### Запуск
Контейнер:
```$bash
make first-run
```

если что-то сломается 
```
make delete
make first-run
```

Сами команды. У нас есть супервизор (не путать с утилитой), который управляет перемещением лифта.
Есть возможность смотреть статус супервизора. Есть возможность накидывать задачи.
- для работы в отдельном терминале запустить команду супервизора и не трогать:
```$bash
make elevator_supervisor 
```
- в другом терминале сделать вызов имитации нажатия на кнопку 
(elevator_call from=1 to=3 - с первого на третий. Супервизор не знает, на какой этаж мы поедем, даже если это есть в условии). Результат работы можно будет увидеть в окне супервизора
```$bash
elevator_call from={from} to={to}
```
- отображение статуса оччередей и лифта
```
elevator_status 
```

### Диаграма
![Diagram](https://i.imgur.com/1AXwqJY.png)

### Ссылки
[Helpiks.org](https://helpiks.org/6-61630.html)

### Known issues
Make file gonna failed message during 
```$bash
make first-run
```
