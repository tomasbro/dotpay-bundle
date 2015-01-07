I Dotpay request - płatność
1. Tworzymy akcję kontrolera "pay"
2. Tworzymy formularz z polami które są potrzebne do przekazania do dotpay
3. Tworzymy widok z formularzem i informacją przekierowywania do dotpay (bez javascript ma być przycisk)
4. W aplikacji używającej tego bundla trzeba zrobić forward do tej stworzonej akcji i dodać parametry: 
    - amount
    - control
    - currency
    ...
    Wszystkie które chcemy ustawić w formularzu w widoku a są dostępne wg dokumentacji dotpay
    Dobrze byłoby aby można było zdefiniować wartości tych pól w konfiguracji i przekazywać tylko te, które są zmienne
    W kontrolerze trzeba sprawdzać czy zostały podane wymagane pola. Chyba tylko amount byłoby obowiązkowe (?)
    W razie braku pól obowiązkowych rzucać wyjątek.
    
    A może by akcję oprzeć o eventy, tzn. rzucalibyśmy 2 eventy - 
    1. DotpayPaymentPreparing
    2. DotpayPaymentPrepared
    
    Pierwsze było by uruchamiane na początku akcji i w jakiś sposób trzeba by pozysykiwać z listenera dane do płatności dotpay.
    Następnie byłoby generowanie formularza płatności, być może walidacja tego formularza, żeby uniknąć błędów przed
    wysłaniem do dotpay. W razie błędów w formularzu, rzucany byłby wyjątek
    Po poprawnej walidacji byłby rzucany drugi event, gdyby ktoś coś jeszcze chciał jeszcze wykonać tuż przed przekierowaniem
    na stronę dotpay.
    I na końcu wyświetlany byłby widok z ukrytym formularzem i przekierowaniem do dotpay.
    W tym podejściu niewiadomą na razie dla mnie jest przekazywanie danych przez listenera - popatrzeć na fos_user_bundle
    i tam rzucane eventy - redirect url jest pobierany z listenerów.
    Po wstępnym przejrzeniu fos_user_bundle, wygląda że dałoby się to tak zrobić - w eventClass dać pole requestParams i 
    do tego setter i getter. requestParams można by dać jako jakiś "paramsBag". W listenerze ustawić wszystkie pola, które
    chcemy ustawić (amount, currency, control itd), a contolerze w dotpayBundle najpierw wszystkie wartości ustawić na 
    domyślne (pobrać z konfiguracji aplikacji, następnie z konfiga bundla). Potem pobrać parametry z eventa i ustawić je
    w request'cie. Następnie zrobić walidację (rzucić wyjątkiem gdyby był jakiś błąd). No i dalej to już z górki: generowanie
    formularza, rzucenie drugim eventem i na końcu wyświetlenie szablonu. (a może GET request - przekierowanie usera 
    301 i dane requesta w urlu? - dać do wyboru w konfiguracji? ).


    
II Dotpay callback
1. Tworzymy akcję kontrolera callback
2. Akcja zwraca zawsze Response 200 "OK"
3. W akcji wywoływana jest walidacja callbacka z dotpay
4. Logujemy wszystkie dane z wszystkich callbacków
4a. Wykorzystujemy FormEvent żeby wywalić extra_fields, bo bez tego formularz nie przejdzie walidacji
5. Rzucamy eventem DotpayCallbackSuccess lub DotpayCallbackFail, w zależności od wyniku walidacji
6. W aplikacji korzystającej z tego bundla podpinamy się pod zdarzenia ww i procesujemy callbacki - eventy przekazują 
formularz lub entity lub po prostu request - zdecydować co będzie najlepsze. Na początek chyba można założyć, że request.
Ale możliwe że formularz umożliwi nam od razu poprawną walidację, z tym że do walidacji potrzebne są rzeczy spoza requesta,
tj. dotpay_id i dotpay_pin. Można zrobić własny constraint jako service, wtedy można wstrzyknąć te parametry do niego
Walidacja musi sprawdzać 3 rzeczy:
1. Ip nadawacy - musi być jednym z listy dotpayValidIp z konfiguracji - jak zwalidować IP
2. md5 z requesta musi być takie samo jak obliczone w aplikacji
3. musi zgadzać się kwota i waluta z requesta z tym co jest w aplikacji (jak przekazać oczekiwaną kwotę i walutę do walidatora?)
    może rzucamy eventami: pre_callback_process, post_callback_process - w pre_callback_process dać metodę getOrginalAmount()
    setOrginalAmount(). domyślnie getOrginalAmount() zwracało by null i jeżeli nie zostało by ustawione w aplikacji korzystającej
    z tego bundla to nie byłoby szansy żeby przeszło walidację.


Podsumowanie:
+ Nie ma interakcji bundla z bazą


    