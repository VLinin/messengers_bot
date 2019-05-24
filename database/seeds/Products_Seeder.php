<?php

use Illuminate\Database\Seeder;

class Products_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //молочные коктейли 1
        DB::table('products')->insert([
            'id' => 1,
            'name' => 'Молочный коктейль - шейк Snickers, 350мл',
            'description' => '
Любимый и один из самых популярных шоколадных батончиков всех времен- Сникерс!
Теперь в виде молочного коктейля в стильной пластиковой бутылочке со спорт- крышкой.
Маст-хэв для сладкоежек!
',
            'price' => 250,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 1,
            'path' => 'pr1.png',
            'vk' => 456239092
        ]);
        DB::table('image_products')->insert([
            'product_id' => 1,
            'image_id' => 1
        ]);

        DB::table('products')->insert([
            'id' => 2,
            'name' => 'Молочный коктейль Bounty, 350мл',
            'description' => '"Райское наслаждение"- именно эти слова мы вспоминаем при виде Баунти!
Теперь Bounty можно попробовать в виде молочного коктейля со вкусом знаменитой кокосовой шоколадки!',
            'price' => 250,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 2,
            'path' => 'pr2.png',
            'vk' => 456239093
        ]);
        DB::table('image_products')->insert([
            'product_id' => 2,
            'image_id' => 2
        ]);

        DB::table('products')->insert([
            'id' => 3,
            'name' => 'Молочный коктейль-шейк Skittles, 350мл',
            'description' => 'Радуга во всем своем ярком многообразии.
Молочный коктейль с безбашенным фруктовым ассорти от Скитллз.
Попробуйте шейк Skittles, и он станет Вашим любимым лакомством!',
            'price' => 250,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 3,
            'vk' => 456239094,
            'path' => 'pr3.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 3,
            'image_id' => 3
        ]);

        DB::table('products')->insert([
            'id' => 4,
            'name' => 'Молочный коктейль M&M\'s Peanut арахисовая паста, 350мл',
            'description' => 'Молочный коктейль M&M\'s со вкусом арахисового масла в бутылке.
Триста пятьдесят миллилитров вкусного наслаждения арахисовым эмэндэмсом в сладкой глазури!',
            'price' => 250,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 4,
            'vk' => 456239095,
            'path' => 'pr4.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 4,
            'image_id' => 4
        ]);

        DB::table('products')->insert([
            'id' => 5,
            'name' => 'Молочный коктейль шейк Mars, 350мл ',
            'description' => 'Молочный коктейль по мотивам знаменитой шоколадки MARS.
Из Британии прямиком к Вам!',
            'price' => 250,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 5,
            'vk' => 456239096,
            'path' => 'pr5.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 5,
            'image_id' => 5
        ]);

        DB::table('products')->insert([
            'id' => 6,
            'name' => 'Молочный коктейль MilkyWay, 350мл',
            'description' => 'У нас есть молочный коктейль Милки Вей в бутылочке, который порадует Вас в любой день, а еще у бутылочки удобная спортивная крышка, поэтому напиток легко можно положить в сумку или машину, и пить, когда удобно!',
            'price' => 250,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 6,
            'vk' => 456239097,
            'path' => 'pr6.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 6,
            'image_id' => 6
        ]);

        DB::table('products')->insert([
            'id' => 7,
            'name' => 'Молочный коктейль шоколадный M&M\'s, 350мл',
            'description' => 'Популярные драже в глазури теперь и в виде молочного коктейля "ЭмЭндЭмс"
M&M\'s Chocolate Cocktail- милкшейк со вкусом шоколада и глазури! Обязательно попробуйте!',
            'price' => 280,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 7,
            'vk' => 456239098,
            'path' => 'pr7.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 7,
            'image_id' => 7
        ]);

        DB::table('products')->insert([
            'id' => 8,
            'name' => 'Молочный коктейль- шейк TWIX, 350мл ',
            'description' => 'Две знаменитые палочки с нугой и карамелью, перемолотые с молоком в вкуснейший молочный шейк Британского производства.
Сделай паузу, выпей TWIX!',
            'price' => 250,
            'category_id'=> 1,
        ]);
        DB::table('images')->insert([
            'id' => 8,
            'vk' => 456239099,
            'path' => 'pr8.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 8,
            'image_id' => 8
        ]);

        //мармелад 2
        DB::table('products')->insert([
            'id' => 9,
            'name' => 'Docile Sour Banana, мармелад кисленький, бананы, 80 гр.',
            'description' => '
Любители и обожатели мармелада - собирайтесь!
Вкуснецкие мармеладные бананы уже ждут! Просто отличный и недорогой вариант для всех сладкоежек. Мексиканские кисловатые бананы из мармелада порадуют как сладкоежек, так и любителей кисленького!
',
            'price' => 100,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 9,
            'vk' => 456239100,
            'path' => 'pr9.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 9,
            'image_id' => 9
        ]);

        DB::table('products')->insert([
            'id' => 10,
            'name' => 'Docile Sours Fitinha, кислая клубничная мармеладная лента, 80 гр.',
            'description' => 'Удобно, вкусно, кисленько! Самое оно для фанатов мармелада! 
Клубнично- мармеладная лента в кислой обсыпке прилетела к нам из Мексики, и готова радовать сладкоежек- любителей покислее.',
            'price' => 100,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 10,
            'vk' => 456239101,
            'path' => 'pr10.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 10,
            'image_id' => 10
        ]);

        DB::table('products')->insert([
            'id' => 11,
            'name' => 'Мармелад подарочный "Лучшая мама- это ты", 250 мл.',
            'description' => 'Что подарить самому лучшему и нежному человеку в твоей жизни- маме?
Конечно, мама оценит любой твой подарок! Но с этим вкуснейшим мармеладом и простыми, но проникновенными словами, она точно растает.',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 11,
            'vk' => 456239102,
            'path' => 'pr11.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 11,
            'image_id' => 11
        ]);

        DB::table('products')->insert([
            'id' => 12,
            'name' => 'Мармелад подарочный "Лучший папа- это ты", 250 мл.',
            'description' => '
Папы любят подарки! Папы любят мармеладки.
Подари папе внимания и немножко вкусняшек в банке! Ведь при всей своей силе и серьезности - папа тоже любит вкусненькое, и любит тебя!
',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 12,
            'vk' => 456239103,
            'path' => 'pr12.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 12,
            'image_id' => 12
        ]);

        DB::table('products')->insert([
            'id' => 13,
            'name' => 'Мармелад подарочный "Цвет настроения черный", 250 мл.',
            'description' => 'Напала грустняшка? Цвет настроения черный? Или серый, например? Не беда - мармелад поднимет тебе настроение в два счета!
Отличное лекарство от хандры- вкусные черные мармеладки в виде кошек!
Весь мармелад из Испании- невероятно вкусный и приятный на зубок.',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 13,
            'vk' => 456239104,
            'path' => 'pr13.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 13,
            'image_id' => 13
        ]);

        DB::table('products')->insert([
            'id' => 14,
            'name' => 'Мармелад подарочный "С днем рождения", 250 мл.',
            'description' => 'К сожалению, день рождения только раз в году. Но оно того стоит- ведь это всегда встреча с друзьями, много смеха, веселья, и... ПОДАРКОВ!
К любому подарку будет не стыдно, а очень даже кстати приложить баночку вкуснейшего испанского мармелада!',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 14,
            'vk' => 456239105,
            'path' => 'pr14.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 14,
            'image_id' => 14
        ]);

        DB::table('products')->insert([
            'id' => 15,
            'name' => 'Мармелад подарочный "Лучшему другу", 250 мл. ',
            'description' => 'Что подарить твоему бро? Самому четкому человеку в мире? Лучшему другу?
Братюня заслужил вкусняшку- до горла наполненную испанским мармеладом баночку с текстом, который подтверждает, что именно он - самый лучший и четкий друг!',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 15,
            'vk' => 456239106,
            'path' => 'pr15.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 15,
            'image_id' => 15
        ]);

        DB::table('products')->insert([
            'id' => 16,
            'name' => '"Ты мое счастье" испанский мармелад в банке, 210 гр.',
            'description' => '
Вместо тысячи слов дарят Рафаэлло.
А вместо миллиона эмоций - настоящую концентрированную любовь в одной баночке!
И это наш мармелад с пожеланиями на любой случай!
"Ты мое счастье"- простые три слова, содержащие огромную нежность.
А внутри- испанский вкуснейший мармелад!
',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 16,
            'vk' => 456239107,
            'path' => 'pr16.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 16,
            'image_id' => 16
        ]);

        DB::table('products')->insert([
            'id' => 17,
            'name' => '"ЙОУ", испанский мармелад в банке, 210 гр.',
            'description' => '
Минималистично. Понятно. Без пафоса. Йоу.
Испанский мармелад в подарочной баночке.
',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 17,
            'vk' => 456239108,
            'path' => 'pr17.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 17,
            'image_id' => 17
        ]);

        DB::table('products')->insert([
            'id' => 18,
            'name' => 'Мармелад подарочный "Это мармелад", 210 гр. ',
            'description' => '
Для самых очевидных капитанов и адмиралов.
Мармелад в баночке с надписью "Это мармелад". Железобетонный подарок!
',
            'price' => 290,
            'category_id'=> 2,
        ]);
        DB::table('images')->insert([
            'id' => 18,
            'vk' => 456239109,
            'path' => 'pr18.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 18,
            'image_id' => 18
        ]);

        //маршлмэллоу 3
        DB::table('products')->insert([
            'id' => 19,
            'name' => 'Rocky Mountain Classic, ванильный маршмэллоу, 150 гр.',
            'description' => 'Rocky Mountain - настоящий, классический американский маршмэллоу. Именно тот самый, который вы видите в фильмах, тот, что жарят на костре на длинных палочках.',
            'price' => 190,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 19,
            'vk' => 456239110,
            'path' => 'pr19.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 19,
            'image_id' => 19
        ]);

        DB::table('products')->insert([
            'id' => 20,
            'name' => 'Rocky Mountain Fruit, фруктовый маршмэллоу, 150 гр.',
            'description' => 'Rocky Mountain Fruit- американский фруктовый маршмэллоу для жарки на костре или барбекю! Нежнейшие зефирки с фруктовыми вкусовыми оттенками- высший класс!',
            'price' => 190,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 20,
            'vk' => 456239111,
            'path' => 'pr20.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 20,
            'image_id' => 20
        ]);

        DB::table('products')->insert([
            'id' => 21,
            'name' => 'Maxmallows Bears, ванильные медвежата, 250 гр. ',
            'description' => 'Maxmallows- мексиканские популярные Маршмэллоу, славящиеся своим вкусом и невысокой стоимостью!
Ванильные, воздушные и вкусные зефирные медвежата в большой пачке- отличное дополнение к чашке кофе!',
            'price' => 250,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 21,
            'vk' => 456239112,
            'path' => 'pr21.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 21,
            'image_id' => 21
        ]);

        DB::table('products')->insert([
            'id' => 22,
            'name' => 'Mini- Maxmallows, маршмеллоу ванильный, пакет 150 гр.',
            'description' => 'Целый пакет ванильного маршмеллоу из Мексики!
Подойдет для кофе или жарки! Ну или просто так покушать ;-',
            'price' => 149,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 22,
            'vk' => 456239113,
            'path' => 'pr22.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 22,
            'image_id' => 22
        ]);

        DB::table('products')->insert([
            'id' => 23,
            'name' => 'Fluff Marshmallow vanilla, ванильный жидкий зефир, 454гр.',
            'description' => 'Классический жидкий маршмэллоу, или кремовый зефир.
В большой банке, почти полкило счастья!
Скорее намаж его на хлеб! Нет хлеба? Не беда, его можно есть прямо ложкой, так даже порой вкуснее!',
            'price' => 450,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 23,
            'vk' => 456239114,
            'path' => 'pr23.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 23,
            'image_id' => 23
        ]);

        DB::table('products')->insert([
            'id' => 24,
            'name' => 'Fluff Marshmallow ванильный, жидкий маршмэллоу, 213гр',
            'description' => '
Fluff Marshmallow Vanilla- нежный кремовый зефир маршмэллоу от Флафф, то самое "облачко" на вашей ложке!
Классический, нежный и невероятно легкий!
Маршмэллоу от Fluff- отличное начало дня, прекрасно подойдет к завтраку и полднику, понравится всем- от мала до велика!
Флаф- отличное дополнение к сендвичу, крекеру, печенью!',
            'price' => 290,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 24,
            'vk' => 456239115,
            'path' => 'pr24.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 24,
            'image_id' => 24
        ]);

        DB::table('products')->insert([
            'id' => 25,
            'name' => 'Fluff Strawberry клубничный жидкий маршмэллоу, 213гр ',
            'description' => 'Кремовый зефир Marshmallow от FLUFF уже занял в сердцах многих любителей сладкого особое место.
Зефир маршмэллоу можно есть ложками- очень сложно остановиться!',
            'price' => 290,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 25,
            'vk' => 456239116,
            'path' => 'pr25.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 25,
            'image_id' => 25
        ]);

        DB::table('products')->insert([
            'id' => 26,
            'name' => 'Fluff Caramel карамельный жидкий зефир-маршмэллоу, 213гр',
            'description' => '
Кремовая карамель!  
Это же просто бомба! Для всех любителей мармэллоу и всевозможных паст!
Намажьте кремовый зефир на хлеб, или ешьте ложкой- в любом случае, это невероятно вкусно!
',
            'price' => 290,
            'category_id'=> 3,
        ]);
        DB::table('images')->insert([
            'id' => 26,
            'vk' => 456239117,
            'path' => 'pr26.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 26,
            'image_id' => 26
        ]);

        //пончики 4
        DB::table('products')->insert([
            'id' => 27,
            'name' => 'Today Bear Cake, кекс "мишка", 45 гр.',
            'description' => '
Today Bear Cake- мимишный кексик в форме мишки от известного производителя вкуснющих пончиков - кексов Today!
Отличное дополнение к вашей чашечке чая!
Да еще и выглядит мило, как коричневый медведик, вне конкуренции.
',
            'price' => 39,
            'category_id'=> 4,
        ]);
        DB::table('images')->insert([
            'id' => 27,
            'vk' => 456239118,
            'path' => 'pr27.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 27,
            'image_id' => 27
        ]);

        DB::table('products')->insert([
            'id' => 28,
            'name' => 'Today Donut Strawberry, кекс- пончик клубника, 50 гр.',
            'description' => 'Кекс - пончик с клубничной глазурью и начинкой',
            'price' => 39,
            'category_id'=> 4,
        ]);
        DB::table('images')->insert([
            'id' => 28,
            'vk' => 456239119,
            'path' => 'pr28.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 28,
            'image_id' => 28
        ]);

        DB::table('products')->insert([
            'id' => 29,
            'name' => 'Today Donut Banana, кекс- пончик банан, 50 гр.',
            'description' => '
Нежный пончик с ярко выраженным банановым вкусом!
Производство Турция.
',
            'price' => 39,
            'category_id'=> 4,
        ]);
        DB::table('images')->insert([
            'id' => 29,
            'vk' => 456239120,
            'path' => 'pr29.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 29,
            'image_id' => 29
        ]);

        DB::table('products')->insert([
            'id' => 30,
            'name' => 'Today Donut Caramel, кекс- пончик карамель, 50 гр. ',
            'description' => 'Пончик с шоколадом и карамелью.',
            'price' => 39,
            'category_id'=> 4,
        ]);
        DB::table('images')->insert([
            'id' => 30,
            'vk' => 456239121,
            'path' => 'pr30.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 30,
            'image_id' => 30
        ]);

        DB::table('products')->insert([
            'id' => 31,
            'name' => 'Today Donut Cherry, кекс- пончик вишня, 50 гр.',
            'description' => 'Пончик с шоколадом и вишневым вкусом!',
            'price' => 39,
            'category_id'=> 4,
        ]);
        DB::table('images')->insert([
            'id' => 31,
            'vk' => 456239122,
            'path' => 'pr31.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 31,
            'image_id' => 31
        ]);

        DB::table('products')->insert([
            'id' => 32,
            'name' => 'Today Donut Cocount, кекс- пончик кокос, 50 гр. ',
            'description' => 'Пончик с начинкой из кокосового крема. Сверху шоколад и обсыпка из кокосовой стружки.',
            'price' => 39,
            'category_id'=> 4,
        ]);
        DB::table('images')->insert([
            'id' => 32,
            'vk' => 456239123,
            'path' => 'pr32.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 32,
            'image_id' => 32
        ]);

        DB::table('products')->insert([
            'id' => 33,
            'name' => 'Today Donut Cocoa, кекс - пончик какао, 50 гр. ',
            'description' => 'Пончик - кекс с шоколадной оболочкой и шоколадом внутри.',
            'price' => 39,
            'category_id'=> 4,
        ]);
        DB::table('images')->insert([
            'id' => 33,
            'vk' => 456239124,
            'path' => 'pr33.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 33,
            'image_id' => 33
        ]);

        //шоколад 5
        DB::table('products')->insert([
            'id' => 34,
            'name' => 'Шоколад Schogetten Stracciatella, мороженое с шоколадом, 100гр. ',
            'description' => 'Немецкий шоколад Shogetten со вкусом сливочного мороженого с шоколадной крошкой!',
            'price' => 120,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 34,
            'vk' => 456239125,
            'path' => 'pr34.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 34,
            'image_id' => 34
        ]);

        DB::table('products')->insert([
            'id' => 35,
            'name' => 'Шоколад с молоком Shogetten For Kids детский, 100 гр. ',
            'description' => 'Детский молочный шоколад Shogetten с натуральным молоком!',
            'price' => 120,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 35,
            'vk' => 456239126,
            'path' => 'pr35.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 35,
            'image_id' => 35
        ]);

        DB::table('products')->insert([
            'id' => 36,
            'name' => 'Шоколад Schogetten Nugat, ореховое пралине, 100 гр.',
            'description' => 'Немецкий шоколад, поделенный на маленькие кубики, с начинкой из ореховой нуги!',
            'price' => 120,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 36,
            'vk' => 456239127,
            'path' => 'pr36.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 36,
            'image_id' => 36
        ]);

        DB::table('products')->insert([
            'id' => 37,
            'name' => 'Shogetten Edel- Alpenvollmilch- Haselnuss, с фундуком, 100 гр ',
            'description' => 'Schogetten сразу разделен на кубики, шоколад не нужно ломать, можно сразу отправлять вкусняшку по назначению - в рот!
Настоящий шоколад из Германии, с кусочками свежего фундука- невероятная атмосфера вкуса!',
            'price' => 120,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 37,
            'vk' => 456239128,
            'path' => 'pr37.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 37,
            'image_id' => 37
        ]);

        DB::table('products')->insert([
            'id' => 38,
            'name' => 'Schogetten Joghurt Erdbeer, с клубничным йогуртом, 100 гр. ',
            'description' => 'Клубничный йогурт- любимое сочетание основных европейских производителей шоколада.
Шоготтен не остался в стороне- встречайте, Shogotten Joghurt Erdbeer- молочный шоколад, клубничный йогурт!',
            'price' => 120,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 38,
            'vk' => 456239129,
            'path' => 'pr38.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 38,
            'image_id' => 38
        ]);

        DB::table('products')->insert([
            'id' => 39,
            'name' => 'Milka TUC, Милка ТУК, 35 гр.',
            'description' => '
Milka TUC - мини-версия шоколадки Милка Тук с одноименным знаменитым крекером!
Отличное сочетание крекера и молочного шоколада, зайдет отъявленным сладкоежкам. А еще- это совсем недорого, ведь импортная версия шоколадки еще и размером поменьше- как раз на легкий перекус!
',
            'price' => 69,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 39,
            'vk' => 456239130,
            'path' => 'pr39.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 39,
            'image_id' => 39
        ]);

        DB::table('products')->insert([
            'id' => 40,
            'name' => 'Шоколад Milka LU, 35 гр. ',
            'description' => 'Мини-плитка шоколада Milka с бисквитом LU',
            'price' => 59,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 40,
            'vk' => 456239131,
            'path' => 'pr40.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 40,
            'image_id' => 40
        ]);

        DB::table('products')->insert([
            'id' => 41,
            'name' => 'Мини-шоколадная плитка Milka Milkinis Sticks, 44гр ',
            'description' => 'Milka Milkinis Sticks- это маленькие, невероятно нежные шоколадные палочки с молочной начинкой в красивой, компактной и удобной картонной коробочке, которую так легко взять с собой!
Маленький подниматель настроения теперь в твоем кармане!',
            'price' => 90,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 41,
            'vk' => 456239132,
            'path' => 'pr41.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 41,
            'image_id' => 41
        ]);

        DB::table('products')->insert([
            'id' => 42,
            'name' => 'Шоколад Milka Oreo, 100гр',
            'description' => 'ачество и нежный вкус молочного шоколада Milka- визитная карточка бренда.
Предлагаем попробовать новый вкус, сочетающий в себе всеми любимую Милку и печенье Oreo внутри!',
            'price' => 120,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 42,
            'vk' => 456239133,
            'path' => 'pr42.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 42,
            'image_id' => 42
        ]);

        DB::table('products')->insert([
            'id' => 43,
            'name' => 'Milka Oreo Sandwich, милка с целым Орео, 92 гр. ',
            'description' => 'Milka Sandwich Oreo - шоколадка с целыми Орео и молочным кремом',
            'price' => 130,
            'category_id'=> 5,
        ]);
        DB::table('images')->insert([
            'id' => 43,
            'vk' => 456239134,
            'path' => 'pr43.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 43,
            'image_id' => 43
        ]);

        //газированные напитки 6
        DB::table('products')->insert([
            'id' => 44,
            'name' => 'Газировка Chupa Chups Grape, виноград, 345 мл. ',
            'description' => 'Газировка со вкусом виноградного Чупа-Чупса!',
            'price' => 90,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 44,
            'vk' => 456239135,
            'path' => 'pr44.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 44,
            'image_id' => 44
        ]);

        DB::table('products')->insert([
            'id' => 45,
            'name' => 'Газировка Chupa Chups Orange, апельсин, 345 мл.',
            'description' => 'Газировка Chupa Chups со вкусом апельсиновой конфеты!',
            'price' => 90,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 45,
            'vk' => 456239136,
            'path' => 'pr45.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 45,
            'image_id' => 45
        ]);

        DB::table('products')->insert([
            'id' => 46,
            'name' => 'Газировка Chupa Chups Strawberry Creme, клубничный крем, 345 мл.',
            'description' => 'Газировка Chupa Chups Strawberry Creme - вкус клубники со сливками.',
            'price' => 90,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 46,
            'vk' => 456239137,
            'path' => 'pr46.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 46,
            'image_id' => 46
        ]);

        DB::table('products')->insert([
            'id' => 47,
            'name' => 'MUG Root Beer, рутбир 0.355л, США ',
            'description' => 'MUG Root Beer - газированный напиток\корневое пиво марки МагРут Бир, принадлежащей PepsiCo.
Рутбир- известный традиционный напиток Северной Америки, имеющий специфический вкус коры дерева сассафрас!',
            'price' => 99,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 47,
            'vk' => 456239138,
            'path' => 'pr47.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 47,
            'image_id' => 47
        ]);

        DB::table('products')->insert([
            'id' => 48,
            'name' => 'A&W Cream Soda, крем сода, 0.355л ',
            'description' => 'A&W Cream Soda- замечательный напиток с карамельной ванилью, впервые созданный в 1986 году.
Незабываемый нежный ванильный вкус никого не оставит равнодушным!',
            'price' => 90,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 48,
            'vk' => 456239139,
            'path' => 'pr48.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 48,
            'image_id' => 48
        ]);

        DB::table('products')->insert([
            'id' => 49,
            'name' => 'A&W Root Beer, корневое "пиво", рутбир, 0.355л ',
            'description' => 'A&W Root Beer - популярный безалкагольный газированный напиток, также известный, как Сассапарилла.
Рутбир (или корневое пиво)- один из традиционных напитков северной америки, изготавливаемый на основе коры дерева сассафрас.',
            'price' => 90,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 49,
            'vk' => 456239140,
            'path' => 'pr49.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 49,
            'image_id' => 49
        ]);

        DB::table('products')->insert([
            'id' => 50,
            'name' => 'Dr Pepper Vanilla Float, Доктор Пеппер Ванилла Флоат, ж\б 0,355 л.',
            'description' => 'Dr Pepper Vanilla Float (Доктор Пеппер Ванилла Флот) - напиток со вкусом ванильного мороженого.
Ванилла Флоат- это ограниченная серия Doctor Pepper, на заводе в США она проливается только в определенные месяцы в году, и обладает оригинальным вкусом ванили и ноткой вишни.',
            'price' => 89,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 50,
            'vk' => 456239141,
            'path' => 'pr50.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 50,
            'image_id' => 50
        ]);

        DB::table('products')->insert([
            'id' => 51,
            'name' => 'BIG RED, напиток со вкусом жвачки, 355 мл ',
            'description' => 'Уникальный микс ванили, апельсина и лимона придает напитку тот самый необычный вкус, очень похожий на жвачку!
Попробуй, если фанат газировки- это действительно необычный вкус!',
            'price' => 89,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 51,
            'vk' => 456239142,
            'path' => 'pr51.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 51,
            'image_id' => 51
        ]);

        DB::table('products')->insert([
            'id' => 52,
            'name' => 'BIG BLUE, синяя газировка, 355 мл ',
            'description' => 'Синяя фруктовая газировка от техасской BIG! Вкус ягод и фирменный привкус жвачки- удачное и отчасти случайное сочетание ингридиентов! Благодаря этой случайности, BIG преобрела широкую известность в США.',
            'price' => 89,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 52,
            'vk' => 456239143,
            'path' => 'pr52.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 52,
            'image_id' => 52
        ]);

        DB::table('products')->insert([
            'id' => 53,
            'name' => 'Crush Pineapple, Краш ананас, США 0.355л ',
            'description' => 'Crush Pineapple - яркий насыщенный вкус, максимально приближенный к настоящему, ведь Краш- мастера по фруктам!
Любителям экзотический фруктов- обязательно к употреблению!
Ностальгические нотки 90-х, хоть Краш у нас был только в классическом виде, всколыхнутся в вашем нутре',
            'price' => 89,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 53,
            'vk' => 456239144,
            'path' => 'pr53.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 53,
            'image_id' => 53
        ]);

        DB::table('products')->insert([
            'id' => 54,
            'name' => 'Crush Strawberry, Краш Клубника, США 0.355л ',
            'description' => '
Crush Strawberry - как будто пьешь настоящую клубнику.
Попробуйте замечательный газированный клубничный напиток от Краш- почувствуйте сладкий вкус самой летней ягоды, в виде американского напитка.
',
            'price' => 89,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 54,
            'vk' => 456239145,
            'path' => 'pr54.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 54,
            'image_id' => 54
        ]);

        DB::table('products')->insert([
            'id' => 55,
            'name' => 'Crush Peach, Краш персик, США 0.355л ',
            'description' => '
Crush Peach - это чудесный напиток для любителей вкусной гаизровки!
Да, Краш не так распиарен и популярен, как Кока-Кола или Фанта, НО! Несмотря на все, Crush- наверное единственный напиток, лучше всех передающий натуральный вкус фруктов!
Попробуйте этот замечательный "жидкий персик"!
',
            'price' => 89,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 55,
            'vk' => 456239146,
            'path' => 'pr55.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 55,
            'image_id' => 55
        ]);

        DB::table('products')->insert([
            'id' => 56,
            'name' => 'Crush Grape, Краш виноград, США 0.355л ',
            'description' => 'Crush Grape- газированный прохладительный напиток со вкусом винограда!
Вкусы торговой марки "КРАШ" отличаются максимально приближенными к настоящим фруктам, имеют насыщенный аромат и вкус!',
            'price' => 89,
            'category_id'=> 6,
        ]);
        DB::table('images')->insert([
            'id' => 56,
            'vk' => 456239147,
            'path' => 'pr56.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 56,
            'image_id' => 56
        ]);

        //конфеты и жевательная резинка 7
        DB::table('products')->insert([
            'id' => 57,
            'name' => 'Abe-seika matcha, пачка конфет, вкус чая матча, 65 гр.',
            'description' => '
Abe-Seika - нежные жевательные конфетки со вкусом знаменитого чая матча. Целая пачка жевательных конфет, похожи они по консистенции на мягкую карамель. Абсолютное качество и культура производства и потребления в Японии не оставляет сомнений в натуральности вкуса.
Все, что произведено в Японии - уже гарант качества и лучших ингридиентов.
',
            'price' => 340,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 57,
            'vk' => 456239148,
            'path' => 'pr57.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 57,
            'image_id' => 57
        ]);

        DB::table('products')->insert([
            'id' => 58,
            'name' => 'Abe-seika strawberry, мягкая карамель, вкус клубники, пачка конфет, 65 гр.',
            'description' => 'Abe-Seika- нежные жевательные конфетки с ярким ароматом и вкусом свежей клубники!
Это одна из любимых ягод японцев, как и русских, в общем-то. Очень нежные и вкусные, а главное - качественные конфетки из Страны Восходящего Солнца.',
            'price' => 290,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 58,
            'vk' => 456239149,
            'path' => 'pr58.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 58,
            'image_id' => 58
        ]);

        DB::table('products')->insert([
            'id' => 59,
            'name' => 'Драже Wonka Nerds Grape Strawberry, клубника-виноград 46,7гр',
            'description' => 'Конфетки со вкусом клубники и винограда- каждый вкус в отдельном кармашке в коробочке!',
            'price' => 140,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 59,
            'vk' => 456239150,
            'path' => 'pr59.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 59,
            'image_id' => 59
        ]);

        DB::table('products')->insert([
            'id' => 60,
            'name' => 'Жвачка в рулоне Tubble Lutti Roll Up tutti, 29 гр.',
            'description' => 'Жвачка в рулоне от Lutti - создателя знаменитой Tubble Gum!
Ностальгия и удобство - пластиковый контейнер этой жвачки позволяет всюду брать ее с собой.',
            'price' => 99,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 60,
            'vk' => 456239151,
            'path' => 'pr60.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 60,
            'image_id' => 60
        ]);

        DB::table('products')->insert([
            'id' => 61,
            'name' => 'Tubble Gum Smiley, вкус лимона, 35 гр. ',
            'description' => 'Новая жвачка в тюбике с ярким цитрусовым вкусом!',
            'price' => 120,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 61,
            'vk' => 456239152,
            'path' => 'pr61.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 61,
            'image_id' => 61
        ]);

        DB::table('products')->insert([
            'id' => 62,
            'name' => 'Жвачка в тюбике Tubble Gum Tutti, 35 гр ',
            'description' => 'Популярная среди молодежи- жидкая жвачка со вкусом мультифрукта.',
            'price' => 120,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 62,
            'vk' => 456239153,
            'path' => 'pr62.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 62,
            'image_id' => 62
        ]);

        DB::table('products')->insert([
            'id' => 63,
            'name' => 'Конфеты Jelly Belly Bertie Bott\'s Harry Potter, 34гр',
            'description' => 'Окунись в Волшебный мир!
Наслаждайся волшебными бобами Берти Боттс с друзьями, или исподтишка подкинь пару конфеток маглам! Вот веселья-то будет!',
            'price' => 280,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 63,
            'vk' => 456239154,
            'path' => 'pr63.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 63,
            'image_id' => 63
        ]);

        DB::table('products')->insert([
            'id' => 64,
            'name' => 'Драже Jelly Belly Ice Cream Parlor, ассорти мороженое, 87 гр. ',
            'description' => 'Любителям самого летнего лакомства посвящается!
Максимально приближенные к реальности вкусы, самые крутые конфеты в мире!',
            'price' => 300,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 64,
            'vk' => 456239155,
            'path' => 'pr64.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 64,
            'image_id' => 64
        ]);

        DB::table('products')->insert([
            'id' => 65,
            'name' => 'Драже Jelly Belly Champagne, вкус шампанского (б/а), 100гр',
            'description' => '

Сказочный и праздничный вкус ШАМПАНСКОГО с Jelly Belly Champagne!
Смакуйте незабываемый, сладкий и покалывающий вкус Ваших любимых пузыриков, и совершенно без алкоголя!
Ваше здоровье!
',
            'price' => 300,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 65,
            'vk' => 456239156,
            'path' => 'pr65.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 65,
            'image_id' => 65
        ]);

        DB::table('products')->insert([
            'id' => 66,
            'name' => 'Кислые конфеты Toxic Waste RED Drum, 42 гр',
            'description' => 'Самые кислые конфеты в мире!
В красном ядерном бочонке!',
            'price' => 290,
            'category_id'=> 7,
        ]);
        DB::table('images')->insert([
            'id' => 66,
            'vk' => 456239157,
            'path' => 'pr66.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 66,
            'image_id' => 66
        ]);

        //пасты 8
        DB::table('products')->insert([
            'id' => 67,
            'name' => 'Nutkao Snack, палочки с шоколадной пастой, 52 гр',
            'description' => 'Два отделения в стаканчике- с шоколадной пастой и с хрустящими бисквитными палочками.',
            'price' => 120,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 67,
            'vk' => 456239158,
            'path' => 'pr67.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 67,
            'image_id' => 67
        ]);

        DB::table('products')->insert([
            'id' => 68,
            'name' => 'Паста M&M\'s, банка 200 гр. ',
            'description' => 'Шоколадная паста с круглыми цветными драже из сладкой глазури.',
            'price' => 450,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 68,
            'vk' => 456239159,
            'path' => 'pr68.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 68,
            'image_id' => 68
        ]);

        DB::table('products')->insert([
            'id' => 69,
            'name' => 'Арахисовая паста American Fresh Peanut Butter with Honey, с медом, 340 гр.',
            'description' => 'Настоящая американская арахисовая паста с добавлением меда!',
            'price' => 459,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 69,
            'vk' => 456239160,
            'path' => 'pr69.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 69,
            'image_id' => 69
        ]);

        DB::table('products')->insert([
            'id' =>70,
            'name' => 'Паста Milky Way, шоколадно- молочная, 200гр ',
            'description' => 'Шоколадная паста Милки Вэй- разделена на две части- шоколадную и молочную!
Тот самый вкус из детства- молочный батончик Milky Way, в виде пасты, которую так приятно намазать на хлеб или просто есть ложкой прямо из банки!',
            'price' => 390,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 70,
            'vk' => 456239161,
            'path' => 'pr70.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 70,
            'image_id' => 70
        ]);

        DB::table('products')->insert([
            'id' => 71,
            'name' => 'Паста Twix с кусочками печенья, 200гр ',
            'description' => 'Теперь вы можете намазать на хлеб TWIX!
Две палочки знаменитого шоколада с карамелью уже на вашем столе!',
            'price' => 390,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 71,
            'vk' => 456239162,
            'path' => 'pr71.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 71,
            'image_id' => 71
        ]);

        DB::table('products')->insert([
            'id' => 72,
            'name' => 'Паста Bounty с кокосовой стружкой, 200гр',
            'description' => 'Райское наслаждение к завтраку!
Паста Bounty с кокосовой стружкой отлично подойдет как лакомство в любое время дня!',
            'price' => 390,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 72,
            'vk' => 456239163,
            'path' => 'pr72.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 72,
            'image_id' => 72
        ]);

        DB::table('products')->insert([
            'id' => 73,
            'name' => 'Паста Maltesers Teasers, 200гр',
            'description' => 'Паста-спред от Matesers - это то, что поднимает настроение с утра!
Нежная шоколадная масса с кусочками того самого Мальтизерс, полюбившегося многим россиянам!',
            'price' => 390,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 73,
            'vk' => 456239164,
            'path' => 'pr73.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 73,
            'image_id' => 73
        ]);

        DB::table('products')->insert([
            'id' => 74,
            'name' => 'Арахисовая паста с дробленым орехом American Fresh Crunchy, 340гр',
            'description' => 'Арахисовая паста American Fresh- настоящее лакомство из натурального перетертого обжаренного арахиса.',
            'price' => 330,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 74,
            'vk' => 456239165,
            'path' => 'pr74.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 74,
            'image_id' => 74
        ]);

        DB::table('products')->insert([
            'id' => 75,
            'name' => 'Арахисовая паста с кленовым сиропом American Fresh Creamy Maple, 340гр ',
            'description' => 'Замечательная кремовая арахисовая паста от American Fresh теперь с добавлением натурального кленового сиропа!',
            'price' => 330,
            'category_id'=> 8,
        ]);
        DB::table('images')->insert([
            'id' => 75,
            'vk' => 456239166,
            'path' => 'pr75.png'
        ]);
        DB::table('image_products')->insert([
            'product_id' => 75,
            'image_id' => 75
        ]);

    }
}
