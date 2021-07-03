DROP TYPE IF EXISTS state_product CASCADE;

DROP TYPE IF EXISTS notif_type CASCADE;

DROP TYPE IF EXISTS user_type CASCADE;

DROP TYPE IF EXISTS category_type CASCADE;

DROP TYPE IF EXISTS conseq_type CASCADE;

DROP TYPE IF EXISTS den_type CASCADE;

DROP INDEX IF EXISTS auction_date_end_index;
DROP INDEX IF EXISTS product_id_owner_index;
DROP INDEX IF EXISTS bidding_id_auction_index;
DROP INDEX IF EXISTS comment_id_index;
DROP INDEX IF EXISTS notification_id_user_index;
DROP INDEX IF EXISTS user_username_index; 
DROP INDEX IF EXISTS idx_fts_product;
DROP INDEX IF EXISTS idx_fts_auth;

DROP TABLE IF EXISTS transactions CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS buyitnows CASCADE;
DROP TABLE IF EXISTS auctions CASCADE;
DROP TABLE IF EXISTS report_products CASCADE;
DROP TABLE IF EXISTS report_users CASCADE;
DROP TABLE IF EXISTS report_comments CASCADE;
DROP TABLE IF EXISTS biddings CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS reports CASCADE;
DROP TABLE IF EXISTS postals CASCADE;
DROP TABLE IF EXISTS citys CASCADE;
DROP TABLE IF EXISTS admins CASCADE;


-- Types

CREATE TYPE state_product AS ENUM ('active', 'inactive', 'removed', 'cancelled', 'bought');

CREATE TYPE notif_type AS ENUM ('surpassed', 'payment', 'bid', 'end_of_auction', 'buy', 'comment');

CREATE TYPE user_type AS ENUM ('active', 'banned', 'suspended', 'inactive');

CREATE TYPE category_type AS ENUM ('antiques', 'art', 'crafts', 'baby', 'travel', 'electronics','toys', 'cars', 'sports', 'house_garden', 'collecting', 'computers', 'music', 'musical_instruments', 'movies', 'photo','watches','comics','stamps', 'stationary', 'bargains', 'pottery', 'memorabilia_portugal' , 'clothing_and_accessories', 'health_beauty', 'philately', 'video_games', 'coins');

CREATE TYPE conseq_type AS ENUM ('suspend', 'ban', 'do_nothing');

CREATE TYPE den_type AS ENUM ('assume', 'assumed', 'done');


--Tables

CREATE TABLE citys (
    id_city serial PRIMARY KEY,
    city_name text NOT NULL
    );

CREATE TABLE postals(
    id_postal serial PRIMARY KEY,
    postal_code integer NOT NULL,
    id_city INTEGER REFERENCES citys (id_city) ON UPDATE CASCADE
);



CREATE TABLE users (
    id serial PRIMARY KEY ,
    username text   UNIQUE NOT NULL,
    email text      UNIQUE NOT NULL,
    name text   NOT NULL,
    password text    NOT NULL,
    photo text,     
    description text,
    date_register date DEFAULT CURRENT_TIMESTAMP, 
    state_user user_type DEFAULT 'active' NOT NULL,
    phone_number integer NOT NULL,
    address text NOT NULL,
    id_postal INTEGER REFERENCES postals (id_postal) ON UPDATE CASCADE,
    birth_date date NOT NULL,
    total_votes integer,
    CONSTRAINT age_ck CHECK (CURRENT_TIMESTAMP - birth_date >= INTERVAL '18 years' )
);


CREATE TABLE products (
    id serial PRIMARY KEY ,
    date_placement timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    name_product text NOT NULL,
    description text NOT NULL,
    photo text,
    state_product state_product DEFAULT 'active' NOT NULL,
    category category_type NOT NULL,
    is_new boolean NOT NULL,
    id_owner integer REFERENCES users (id) ON UPDATE CASCADE
);

CREATE TABLE buyitnows (
    id_buy serial PRIMARY KEY REFERENCES products (id) ON UPDATE CASCADE,
    date_end timestamp NOT NULL,
    final_value integer NOT NULL,
    CONSTRAINT final_value_ck CHECK (final_value > 0 )
       
);

CREATE TABLE auctions (
    id_auction serial PRIMARY KEY REFERENCES products (id) ON UPDATE CASCADE,
    date_end_auction timestamp NOT NULL,
    bidding_base integer NOT NULL,
    final_value integer NOT NULL,
    CONSTRAINT bidding_base_ck CHECK( bidding_base > 0 ),
    CONSTRAINT final_value_ck CHECK( final_value > 0 ),
    CONSTRAINT highest_greater_base CHECK( final_value >= bidding_base )
    
);

CREATE TABLE biddings (
    id_bid serial PRIMARY KEY ,
    id_auction integer  REFERENCES auctions(id_auction) ON UPDATE CASCADE,
    bidder integer  REFERENCES users (id) ON UPDATE CASCADE,
    value_bid integer NOT NULL,
    bidding_date timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL

);



CREATE TABLE transactions (
    id_transac  serial PRIMARY KEY ,
    id_buyer integer REFERENCES users (id) ON UPDATE CASCADE,
    id_seller integer REFERENCES users (id) ON UPDATE CASCADE,
    id integer REFERENCES products(id) ON UPDATE CASCADE,
    vote_inSeller integer,
    vote_inBuyer integer,
    date_payment timestamp,
    value integer NOT NULL,
    CONSTRAINT vote_seller_ck CHECK (vote_inSeller >= 0 AND vote_inSeller <= 5),
    CONSTRAINT vote_buyer_ck CHECK (vote_inBuyer >= 0 AND vote_inBuyer <= 5)

);




CREATE TABLE comments (
    id_comment serial PRIMARY KEY ,
    id_commenter integer REFERENCES users(id) ON UPDATE CASCADE,
    id integer REFERENCES products(id) ON UPDATE CASCADE,
    date_comment timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    msg_ofComment text NOT NULL,
    comment_likes integer DEFAULT 0
);

CREATE TABLE notifications (
    id_notif serial PRIMARY KEY,
    id_user integer REFERENCES users (id) ON UPDATE CASCADE,  
    is_new boolean NOT NULL,
    text_notification text NOT NULL,
    type_ofNotification notif_type NOT NULL,
    id_item integer REFERENCES products (id),
    id_comment integer REFERENCES comments (id_comment),
    constraint chk_only_one CHECK( (id_item IS NOT NULL AND id_comment is NULL) OR (id_comment IS NOT NULL AND id_item is NULL))
);



CREATE TABLE admins (
    id_admin serial PRIMARY KEY,
    username text   UNIQUE NOT NULL,
    email text      UNIQUE NOT NULL,
    name text   NOT NULL,
    password text    NOT NULL,
    photo text,     
    description text,
    date_register timestamp DEFAULT CURRENT_TIMESTAMP, 
    state_user user_type DEFAULT 'active' NOT NULL 
    
);

CREATE TABLE reports (
    id serial PRIMARY KEY ,
    id_admin integer REFERENCES admins (id_admin) ON UPDATE CASCADE,
    id_punished integer REFERENCES users (id) ON UPDATE CASCADE,
    consequence conseq_type,
    state_report den_type NOT NULL,
    observation_admin text,
    date_report date DEFAULT CURRENT_TIMESTAMP NOT NULL,
    reason text NOT NULL,
    text_report text NOT NULL,
    date_begin_punishement date ,
    punishement_span integer,
    id_reporter integer REFERENCES users(id)  ON UPDATE CASCADE NOT NULL
);


CREATE TABLE report_users (
    id_report serial REFERENCES reports PRIMARY KEY,
    id_user integer REFERENCES users(id) ON UPDATE CASCADE NOT NULL 
);



CREATE TABLE report_products (
    id_report serial REFERENCES reports PRIMARY KEY,
    id_product integer REFERENCES products(id) ON UPDATE CASCADE NOT NULL
);

/*
CREATE OR REPLACE FUNCTION update_reported_product()
RETURNS trigger AS $BODY$

DECLARE
    status state_product;
BEGIN
IF EXISTS (select 1 FROM products WHERE id = new.id ) 
    THEN UPDATE products SET state_product = 'inactive' WHERE id = new.id;
    RETURN NEW;
ELSE 
   RAISE EXCEPTION 'Reported product doesn´t exist in products';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER verify_report_product AFTER INSERT ON report_products 
FOR EACH ROW EXECUTE PROCEDURE  update_reported_product();



CREATE OR REPLACE FUNCTION update_unreported_product()
RETURNS trigger AS $BODY$

DECLARE
    status state_product;
BEGIN
UPDATE products SET state_product = 'active' WHERE id = old.id;
RETURN OLD;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER verify_unreport_product BEFORE DELETE ON report_products 
FOR EACH ROW EXECUTE PROCEDURE  update_unreported_product();
*/

CREATE TABLE report_comments (
    id_report serial REFERENCES reports PRIMARY KEY,
    id_comment integer REFERENCES comments(id_comment) ON UPDATE CASCADE NOT NULL 
);

--no php $query = "SELECT * FROM auctions WHERE to_tsvector('portuguese', name_product) @@ to_tsquery('portuguese', ?) OR to_tsvector('portuguese', description) @@ to_tsquery('portuguese', ?) LIMIT 5";
CREATE INDEX product_id_owner_index ON products USING hash (id_owner); --IDX01
CREATE INDEX auction_date_end_index ON products USING hash(state_product) ; --IDX02
CREATE INDEX bidding_id_auction_index ON biddings USING hash (id_auction); --IDX03
CREATE INDEX comment_id_product_index ON comments USING hash (id);--IDX04
CREATE INDEX notification_id_user_index ON notifications USING hash (id_user); --IDX05
CREATE INDEX authenticated_username_index ON users USING HASH (username);--IDX06 
ALTER TABLE products ADD COLUMN search tsvector;
--UPDATE product SET search = (setweight(to_tsvector('portuguese', name_product), 'A') || setweight(to_tsvector('portuguese', description), 'B'));
CREATE INDEX idx_fts_auth ON users USING gin(to_tsvector('portuguese', username));--IDX07
CREATE INDEX idx_fts_product ON products USING gin((setweight(to_tsvector('portuguese', name_product), 'A') || setweight(to_tsvector('portuguese', description), 'B')));--IDX08
--ALTER TABLE products ADD language text NOT NULL DEFAULT('portuguese');

/*
CREATE OR REPLACE FUNCTION gin_fts_product(name_product text, description text, language text) 
  RETURNS tsvector
AS
$BODY$
SELECT setweight(to_tsvector($3::regconfig, $1), 'A') || setweight(to_tsvector($3::regconfig, $1), 'B');

$BODY$
LANGUAGE sql
IMMUTABLE;

CREATE INDEX idx_fts_product ON products USING gin(gin_fts_product(name_product, description, language));--IDX08



ALTER TABLE users ADD language text NOT NULL DEFAULT('portuguese');
CREATE OR REPLACE FUNCTION gin_fts_auth(username text, language text) 
  RETURNS tsvector
AS
$BODY$
    SELECT setweight(to_tsvector($2::regconfig, $1), 'A') ;
$BODY$
LANGUAGE sql
IMMUTABLE;

CREATE INDEX idx_fts_auth ON users USING gin(gin_fts_auth(username, language));--IDX07


*/





--------POPULATE
INSERT INTO citys VALUES (1, 'Águeda');                    
INSERT INTO citys VALUES (2, 'Albergaria-a-Velha');        
INSERT INTO citys VALUES (3, 'Anadia');                    
INSERT INTO citys VALUES (4, 'Arouca');                    
INSERT INTO citys VALUES (5, 'Aveiro');                    
INSERT INTO citys VALUES (6, 'Castelo de Paiva');          
INSERT INTO citys VALUES (7, 'Espinho');                   
INSERT INTO citys VALUES (8, 'Estarreja');                 
INSERT INTO citys VALUES (9, 'Santa Maria da Feira');      
INSERT INTO citys VALUES (10, 'Ílhavo');                   
INSERT INTO citys VALUES (11, 'Mealhada');                 
INSERT INTO citys VALUES (12, 'Murtosa');                  
INSERT INTO citys VALUES (13, 'Oliveira de Azeméis');      
INSERT INTO citys VALUES (14, 'Oliveira do Bairro');       
INSERT INTO citys VALUES (15, 'Ovar');                     
INSERT INTO citys VALUES (16, 'São João da Madeira');      
INSERT INTO citys VALUES (17, 'Sever do Vouga');           
INSERT INTO citys VALUES (18, 'Vagos');                    
INSERT INTO citys VALUES (19, 'Vale de Cambra');           
INSERT INTO citys VALUES (20, 'Aljustrel');                
INSERT INTO citys VALUES (21, 'Almodôvar');                
INSERT INTO citys VALUES (22, 'Alvito');                   
INSERT INTO citys VALUES (23, 'Barrancos');                
INSERT INTO citys VALUES (24, 'Beja');                     
INSERT INTO citys VALUES (25, 'Castro Verde');             
INSERT INTO citys VALUES (26, 'Cuba');                     
INSERT INTO citys VALUES (27, 'Ferreira do Alentejo');     
INSERT INTO citys VALUES (28, 'Mértola');                  
INSERT INTO citys VALUES (29, 'Moura');                    
INSERT INTO citys VALUES (30, 'Odemira');                  
INSERT INTO citys VALUES (31, 'Ourique');                  
INSERT INTO citys VALUES (32, 'Serpa');                    
INSERT INTO citys VALUES (33, 'Vidigueira');               
INSERT INTO citys VALUES (34, 'Amares');                   
INSERT INTO citys VALUES (35, 'Barcelos');                 
INSERT INTO citys VALUES (36, 'Braga');                    
INSERT INTO citys VALUES (37, 'Cabeceiras de Basto');      
INSERT INTO citys VALUES (38, 'Celorico de Basto');        
INSERT INTO citys VALUES (39, 'Fafe');                     
INSERT INTO citys VALUES (40, 'Guimarães');                
INSERT INTO citys VALUES (41, 'Póvoa de Lanhoso');         
INSERT INTO citys VALUES (42, 'Terras de Bouro');          
INSERT INTO citys VALUES (43, 'Vieira do Minho');          
INSERT INTO citys VALUES (44, 'Vila Nova de Famalicão');   
INSERT INTO citys VALUES (45, 'Bragança');                 
INSERT INTO citys VALUES (46, 'Carrazeda de Ansiães');     
INSERT INTO citys VALUES (47, 'Freixo de Espada à Cinta'); 
INSERT INTO citys VALUES (48, 'Macedo de Cavaleiros');     
INSERT INTO citys VALUES (49, 'Miranda do Douro');         
INSERT INTO citys VALUES (50, 'Mirandela');                
INSERT INTO citys VALUES (51, 'Mogadouro');                
INSERT INTO citys VALUES (52, 'Torre de Moncorvo');        
INSERT INTO citys VALUES (53, 'Vila Flor');                
INSERT INTO citys VALUES (54, 'Vimioso');                  
INSERT INTO citys VALUES (55, 'Vinhais');                  
INSERT INTO citys VALUES (56, 'Belmonte');                 
INSERT INTO citys VALUES (57, 'Castelo Branco');           
INSERT INTO citys VALUES (58, 'Covilhã');                  
INSERT INTO citys VALUES (59, 'Fundão');                   
INSERT INTO citys VALUES (60, 'Idanha-a-Nova');            
INSERT INTO citys VALUES (61, 'Oleiros');                  
INSERT INTO citys VALUES (62, 'Penamacor');                
INSERT INTO citys VALUES (63, 'Proença-a-Nova');           
INSERT INTO citys VALUES (64, 'Sertã');                    
INSERT INTO citys VALUES (65, 'Vila de Rei');              
INSERT INTO citys VALUES (66, 'Vila Velha de Rodão');      
INSERT INTO citys VALUES (67, 'Arganil');                  
INSERT INTO citys VALUES (68, 'Cantanhede');               
INSERT INTO citys VALUES (69, 'Coimbra');                  
INSERT INTO citys VALUES (70, 'Condeixa-a-Nova');          
INSERT INTO citys VALUES (71, 'Figueira da Foz');          
INSERT INTO citys VALUES (72, 'Góis');                     
INSERT INTO citys VALUES (73, 'Lousã');                    
INSERT INTO citys VALUES (74, 'Mira');                     
INSERT INTO citys VALUES (75, 'Miranda do Corvo');         
INSERT INTO citys VALUES (76, 'Montemor-o-Velho');         
INSERT INTO citys VALUES (77, 'Oliveira do Hospital');     
INSERT INTO citys VALUES (78, 'Pampilhosa da Serra');      
INSERT INTO citys VALUES (79, 'Penacova');                 
INSERT INTO citys VALUES (80, 'Penela');                   
INSERT INTO citys VALUES (81, 'Soure');                    
INSERT INTO citys VALUES (82, 'Tábua');                    
INSERT INTO citys VALUES (83, 'Vila Nova de Poiares');     
INSERT INTO citys VALUES (84, 'Alandroal');                
INSERT INTO citys VALUES (85, 'Arraiolos');                
INSERT INTO citys VALUES (86, 'Borba');                    
INSERT INTO citys VALUES (87, 'Estremoz');                 
INSERT INTO citys VALUES (88, 'Évora');                    
INSERT INTO citys VALUES (89, 'Montemor-o-Novo');          
INSERT INTO citys VALUES (90, 'Mora');                     
INSERT INTO citys VALUES (91, 'Mourão');                   
INSERT INTO citys VALUES (92, 'Portel');                   
INSERT INTO citys VALUES (93, 'Redondo');                  
INSERT INTO citys VALUES (94, 'Vendas Novas');             
INSERT INTO citys VALUES (95, 'Viana do Alentejo');        
INSERT INTO citys VALUES (96, 'Vila Viçosa');              
INSERT INTO citys VALUES (97, 'Albufeira');                
INSERT INTO citys VALUES (98, 'Alcoutim');                 
INSERT INTO citys VALUES (99, 'Aljezur');                  
INSERT INTO citys VALUES (100, 'Castro Marim');            
INSERT INTO citys VALUES (101, 'Faro');                    
INSERT INTO citys VALUES (102, 'Lagoa (Algarve)');         
INSERT INTO citys VALUES (103, 'Lagos');                   
INSERT INTO citys VALUES (104, 'Loulé');                   
INSERT INTO citys VALUES (105, 'Monchique');               
INSERT INTO citys VALUES (106, 'Olhão');                   
INSERT INTO citys VALUES (107, 'Portimão');                
INSERT INTO citys VALUES (108, 'São Brás de Alportel');    
INSERT INTO citys VALUES (109, 'Silves');                  
INSERT INTO citys VALUES (110, 'Tavira');                  
INSERT INTO citys VALUES (111, 'Vila do Bispo');           
INSERT INTO citys VALUES (112, 'Vila Real de Santo António');
INSERT INTO citys VALUES (113, 'Aguiar da Beira');         
INSERT INTO citys VALUES (114, 'Almeida');                 
INSERT INTO citys VALUES (115, 'Celorico da Beira');       
INSERT INTO citys VALUES (116, 'Figueira de Castelo Rodrigo');
INSERT INTO citys VALUES (117, 'Fornos de Algodres');      
INSERT INTO citys VALUES (118, 'Gouveia');                 
INSERT INTO citys VALUES (119, 'Guarda');                  
INSERT INTO citys VALUES (120, 'Manteigas');               
INSERT INTO citys VALUES (121, 'Meda');                    
INSERT INTO citys VALUES (122, 'Pinhel');                  
INSERT INTO citys VALUES (123, 'Sabugal');                 
INSERT INTO citys VALUES (124, 'Seia');                    
INSERT INTO citys VALUES (125, 'Trancoso');                
INSERT INTO citys VALUES (126, 'Vila Nova de Foz Côa');    
INSERT INTO citys VALUES (127, 'Alcobaça');                
INSERT INTO citys VALUES (128, 'Alvaiázere');              
INSERT INTO citys VALUES (129, 'Ansião');                  
INSERT INTO citys VALUES (130, 'Batalha');                 
INSERT INTO citys VALUES (131, 'Bombarral');               
INSERT INTO citys VALUES (132, 'Caldas da Rainha');        
INSERT INTO citys VALUES (133, 'Castanheira de Pêra');     
INSERT INTO citys VALUES (134, 'Leiria');                  
INSERT INTO citys VALUES (135, 'Marinha Grande');          
INSERT INTO citys VALUES (136, 'Nazaré');                  
INSERT INTO citys VALUES (137, 'Óbidos');                  
INSERT INTO citys VALUES (138, 'Pedrógão Grande');         
INSERT INTO citys VALUES (139, 'Peniche');                 
INSERT INTO citys VALUES (140, 'Pombal');                  
INSERT INTO citys VALUES (141, 'Porto de Mós');            
INSERT INTO citys VALUES (142, 'Alenquer');                
INSERT INTO citys VALUES (143, 'Arruda dos Vinhos');       
INSERT INTO citys VALUES (144, 'Azambuja');                
INSERT INTO citys VALUES (145, 'Cadaval');                 
INSERT INTO citys VALUES (146, 'Cascais');                 
INSERT INTO citys VALUES (147, 'Lisboa');                  
INSERT INTO citys VALUES (148, 'Loures');                  
INSERT INTO citys VALUES (149, 'Lourinhã');                
INSERT INTO citys VALUES (150, 'Mafra');                   
INSERT INTO citys VALUES (151, 'Oeiras');                  
INSERT INTO citys VALUES (152, 'Sintra');                  
INSERT INTO citys VALUES (153, 'Sobral de Monte Agraço');  
INSERT INTO citys VALUES (154, 'Torres Vedras');           
INSERT INTO citys VALUES (155, 'Vila Franca de Xira');     
INSERT INTO citys VALUES (156, 'Amadora');                 
INSERT INTO citys VALUES (157, 'Odivelas');                
INSERT INTO citys VALUES (158, 'Alter do Chão');           
INSERT INTO citys VALUES (159, 'Arronches');               
INSERT INTO citys VALUES (160, 'Avis');                    
INSERT INTO citys VALUES (161, 'Campo Maior');             
INSERT INTO citys VALUES (162, 'Castelo de Vide');         
INSERT INTO citys VALUES (163, 'Crato');                   
INSERT INTO citys VALUES (164, 'Elvas');                   
INSERT INTO citys VALUES (165, 'Fronteira');               
INSERT INTO citys VALUES (166, 'Gavião');                  
INSERT INTO citys VALUES (167, 'Marvão');                  
INSERT INTO citys VALUES (168, 'Monforte');                
INSERT INTO citys VALUES (169, 'Nisa');                    
INSERT INTO citys VALUES (170, 'Ponte de Sor');            
INSERT INTO citys VALUES (171, 'Portalegre');              
INSERT INTO citys VALUES (172, 'Sousel');                  
INSERT INTO citys VALUES (173, 'Amarante');                
INSERT INTO citys VALUES (174, 'Baião');                   
INSERT INTO citys VALUES (175, 'Felgueiras');              
INSERT INTO citys VALUES (176, 'Gondomar');                
INSERT INTO citys VALUES (177, 'Lousada');                 
INSERT INTO citys VALUES (178, 'Maia');                    
INSERT INTO citys VALUES (179, 'Marco de Canaveses');      
INSERT INTO citys VALUES (180, 'Matosinhos');              
INSERT INTO citys VALUES (181, 'Paços de Ferreira');       
INSERT INTO citys VALUES (182, 'Paredes');                 
INSERT INTO citys VALUES (183, 'Penafiel');                
INSERT INTO citys VALUES (184, 'Porto');                   
INSERT INTO citys VALUES (185, 'Póvoa de Varzim');         
INSERT INTO citys VALUES (186, 'Santo Tirso');             
INSERT INTO citys VALUES (187, 'Valongo');                 
INSERT INTO citys VALUES (188, 'Vila do Conde');           
INSERT INTO citys VALUES (189, 'Vila Nova de Gaia');       
INSERT INTO citys VALUES (190, 'Trofa');                   
INSERT INTO citys VALUES (191, 'Abrantes');                
INSERT INTO citys VALUES (192, 'Alcanena');                
INSERT INTO citys VALUES (193, 'Almeirim');                
INSERT INTO citys VALUES (194, 'Alpiarça');                
INSERT INTO citys VALUES (195, 'Benavente');               
INSERT INTO citys VALUES (196, 'Cartaxo');                 
INSERT INTO citys VALUES (197, 'Chamusca');                
INSERT INTO citys VALUES (198, 'Constância');              
INSERT INTO citys VALUES (199, 'Coruche');                 
INSERT INTO citys VALUES (200, 'Entroncamento');           
INSERT INTO citys VALUES (201, 'Ferreira do Zêzere');      
INSERT INTO citys VALUES (202, 'Golegâ');                  
INSERT INTO citys VALUES (203, 'Mação');                   
INSERT INTO citys VALUES (204, 'Rio Maior');               
INSERT INTO citys VALUES (205, 'Salvaterra de Magos');     
INSERT INTO citys VALUES (206, 'Santarém');                
INSERT INTO citys VALUES (207, 'Tomar');                   
INSERT INTO citys VALUES (208, 'Torres Novas');            
INSERT INTO citys VALUES (209, 'Vila Nova da Barquinha');  
INSERT INTO citys VALUES (210, 'Ourém');                   
INSERT INTO citys VALUES (211, 'Alcácer do Sal');          
INSERT INTO citys VALUES (212, 'Alcochete');               
INSERT INTO citys VALUES (213, 'Almada');                  
INSERT INTO citys VALUES (214, 'Barreiro');                
INSERT INTO citys VALUES (215, 'Grândola');                
INSERT INTO citys VALUES (216, 'Moita');                   
INSERT INTO citys VALUES (217, 'Montijo');                 
INSERT INTO citys VALUES (218, 'Palmela');                 
INSERT INTO citys VALUES (219, 'Santiago do Cacém');       
INSERT INTO citys VALUES (220, 'Seixal');                  
INSERT INTO citys VALUES (221, 'Sesimbra');                
INSERT INTO citys VALUES (222, 'Setúbal');                 
INSERT INTO citys VALUES (223, 'Sines');                   
INSERT INTO citys VALUES (224, 'Arcos de Valdevez');       
INSERT INTO citys VALUES (225, 'Caminha');                 
INSERT INTO citys VALUES (226, 'Melgaço');                 
INSERT INTO citys VALUES (227, 'Monção');                  
INSERT INTO citys VALUES (228, 'Paredes de Coura');        
INSERT INTO citys VALUES (229, 'Ponte da Barca');          
INSERT INTO citys VALUES (230, 'Ponte de Lima');           
INSERT INTO citys VALUES (231, 'Valença');                 
INSERT INTO citys VALUES (232, 'Viana do Castelo');        
INSERT INTO citys VALUES (233, 'Vila Nova de Cerveira');   
INSERT INTO citys VALUES (234, 'Alijó');                   
INSERT INTO citys VALUES (235, 'Boticas');                 
INSERT INTO citys VALUES (236, 'Chaves');                  
INSERT INTO citys VALUES (237, 'Mondim de Basto');         
INSERT INTO citys VALUES (238, 'Montalegre');              
INSERT INTO citys VALUES (239, 'Murça');                   
INSERT INTO citys VALUES (240, 'Peso da Régua');           
INSERT INTO citys VALUES (241, 'Ribeira de Pena');         
INSERT INTO citys VALUES (242, 'Sabrosa');                 
INSERT INTO citys VALUES (243, 'Santa Marta de Penaguião');
INSERT INTO citys VALUES (244, 'Valpaços');                
INSERT INTO citys VALUES (245, 'Vila Pouca de Aguiar');    
INSERT INTO citys VALUES (246, 'Vila Real');               
INSERT INTO citys VALUES (247, 'Armamar');                 
INSERT INTO citys VALUES (248, 'Carregal do Sal');         
INSERT INTO citys VALUES (249, 'Castro Daire');            
INSERT INTO citys VALUES (250, 'Cinfães');                 
INSERT INTO citys VALUES (251, 'Lamego');                  
INSERT INTO citys VALUES (252, 'Mangualde');               
INSERT INTO citys VALUES (253, 'Moimenta da Beira');       
INSERT INTO citys VALUES (254, 'Mortágua');                
INSERT INTO citys VALUES (255, 'Nelas');                   
INSERT INTO citys VALUES (256, 'Oliveira de Frades');      
INSERT INTO citys VALUES (257, 'Penalva do Castelo');      
INSERT INTO citys VALUES (258, 'Penedono');                
INSERT INTO citys VALUES (259, 'Resende');                 
INSERT INTO citys VALUES (260, 'Santa Comba Dão');         
INSERT INTO citys VALUES (261, 'São João da Pesqueira');   
INSERT INTO citys VALUES (262, 'São Pedro do Sul');        
INSERT INTO citys VALUES (263, 'Sátão');                   
INSERT INTO citys VALUES (264, 'Tabuaço');                 
INSERT INTO citys VALUES (265, 'Tarouca');                 
INSERT INTO citys VALUES (266, 'Tondela');                 
INSERT INTO citys VALUES (267, 'Vila Nova de Paiva');      
INSERT INTO citys VALUES (268, 'Viseu');                   
INSERT INTO citys VALUES (269, 'Vouzela');                 
INSERT INTO citys VALUES (270, 'Calheta (Madeira)');       
INSERT INTO citys VALUES (271, 'Câmara de Lobos');         
INSERT INTO citys VALUES (272, 'Funchal');                 
INSERT INTO citys VALUES (273, 'Machico');                 
INSERT INTO citys VALUES (274, 'Ponta do Sol');            
INSERT INTO citys VALUES (275, 'Porto Moniz');             
INSERT INTO citys VALUES (276, 'Ribeira Brava');           
INSERT INTO citys VALUES (277, 'Santa Cruz');              
INSERT INTO citys VALUES (278, 'Santana');                 
INSERT INTO citys VALUES (279, 'São Vicente');             
INSERT INTO citys VALUES (280, 'Porto Santo');             
INSERT INTO citys VALUES (281, 'Vila do Porto');           
INSERT INTO citys VALUES (282, 'Lagoa (São Miguel)');      
INSERT INTO citys VALUES (283, 'Nordeste');                
INSERT INTO citys VALUES (284, 'Ponta Delgada');           
INSERT INTO citys VALUES (285, 'Povoação');                
INSERT INTO citys VALUES (286, 'Ribeira Grande');          
INSERT INTO citys VALUES (287, 'Vila Franca do Campo');    
INSERT INTO citys VALUES (288, 'Angra do Heroísmo');       
INSERT INTO citys VALUES (289, 'Praia da Vitória');        
INSERT INTO citys VALUES (290, 'Santa Cruz da Graciosa');  
INSERT INTO citys VALUES (291, 'Calheta (São Jorge)');     
INSERT INTO citys VALUES (292, 'Velas');                   
INSERT INTO citys VALUES (293, 'Lajes do Pico');           
INSERT INTO citys VALUES (294, 'Madalena');                
INSERT INTO citys VALUES (295, 'São Roque do Pico');       
INSERT INTO citys VALUES (296, 'Horta');                   
INSERT INTO citys VALUES (297, 'Lajes das Flores');        
INSERT INTO citys VALUES (298, 'Santa Cruz das Flores');   
INSERT INTO citys VALUES (299, 'Corvo');                   

INSERT INTO postals VALUES (1, 3750, 1);                   
INSERT INTO postals VALUES (2, 3754, 1);                   
INSERT INTO postals VALUES (3, 3850, 2);                   
INSERT INTO postals VALUES (4, 3780, 3);                   
INSERT INTO postals VALUES (5, 4540, 4);                   
INSERT INTO postals VALUES (6, 3800, 5);                   
INSERT INTO postals VALUES (7, 3804, 5);                   
INSERT INTO postals VALUES (8, 3810, 5);                   
INSERT INTO postals VALUES (9, 3813, 5);                   
INSERT INTO postals VALUES (10, 3814, 5);                  
INSERT INTO postals VALUES (11, 4550, 6);                  
INSERT INTO postals VALUES (12, 4500, 7);                  
INSERT INTO postals VALUES (13, 4504, 7);                  
INSERT INTO postals VALUES (14, 3860, 8);                  
INSERT INTO postals VALUES (15, 3864, 8);                  
INSERT INTO postals VALUES (16, 3865, 8);                  
INSERT INTO postals VALUES (17, 3700, 9);                  
INSERT INTO postals VALUES (18, 4505, 9);                  
INSERT INTO postals VALUES (19, 4520, 9);                  
INSERT INTO postals VALUES (20, 4525, 9);                  
INSERT INTO postals VALUES (21, 4535, 9);                  
INSERT INTO postals VALUES (22, 3830, 10);                 
INSERT INTO postals VALUES (23, 3020, 11);                 
INSERT INTO postals VALUES (24, 3050, 11);                 
INSERT INTO postals VALUES (25, 3054, 11);                 
INSERT INTO postals VALUES (26, 3870, 12);                 
INSERT INTO postals VALUES (27, 3720, 13);                 
INSERT INTO postals VALUES (28, 3770, 14);                 
INSERT INTO postals VALUES (29, 3774, 14);                 
INSERT INTO postals VALUES (30, 3880, 15);                 
INSERT INTO postals VALUES (31, 3884, 15);                 
INSERT INTO postals VALUES (32, 3885, 15);                 
INSERT INTO postals VALUES (33, 3701, 16);                 
INSERT INTO postals VALUES (34, 3740, 17);                 
INSERT INTO postals VALUES (35, 3744, 17);                 
INSERT INTO postals VALUES (36, 3840, 18);                 
INSERT INTO postals VALUES (37, 3730, 19);                 
INSERT INTO postals VALUES (38, 3734, 19);                 
INSERT INTO postals VALUES (39, 7600, 20);                 
INSERT INTO postals VALUES (40, 7604, 20);                 
INSERT INTO postals VALUES (41, 7700, 21);                 
INSERT INTO postals VALUES (42, 7920, 22);                 
INSERT INTO postals VALUES (43, 7924, 22);                 
INSERT INTO postals VALUES (44, 7230, 23);                 
INSERT INTO postals VALUES (45, 7800, 24);                 
INSERT INTO postals VALUES (46, 7801, 24);                 
INSERT INTO postals VALUES (47, 7780, 25);                 
INSERT INTO postals VALUES (48, 7940, 26);                 
INSERT INTO postals VALUES (49, 7900, 27);                 
INSERT INTO postals VALUES (50, 7750, 28);                 
INSERT INTO postals VALUES (51, 7754, 28);                 
INSERT INTO postals VALUES (52, 7860, 29);                 
INSERT INTO postals VALUES (53, 7864, 29);                 
INSERT INTO postals VALUES (54, 7875, 29);                 
INSERT INTO postals VALUES (55, 7885, 29);                 
INSERT INTO postals VALUES (56, 7630, 30);                 
INSERT INTO postals VALUES (57, 7645, 30);                 
INSERT INTO postals VALUES (58, 7665, 30);                 
INSERT INTO postals VALUES (59, 7670, 31);                 
INSERT INTO postals VALUES (60, 7830, 32);                 
INSERT INTO postals VALUES (61, 7960, 33);                 
INSERT INTO postals VALUES (62, 4720, 34);                 
INSERT INTO postals VALUES (63, 4740, 35);                 
INSERT INTO postals VALUES (64, 4750, 35);                 
INSERT INTO postals VALUES (65, 4754, 35);                 
INSERT INTO postals VALUES (66, 4755, 35);                 
INSERT INTO postals VALUES (67, 4775, 35);                 
INSERT INTO postals VALUES (68, 4905, 35);                 
INSERT INTO postals VALUES (69, 4700, 36);                 
INSERT INTO postals VALUES (70, 4704, 36);                 
INSERT INTO postals VALUES (71, 4705, 36);                 
INSERT INTO postals VALUES (72, 4709, 36);                 
INSERT INTO postals VALUES (73, 4710, 36);                 
INSERT INTO postals VALUES (74, 4714, 36);                 
INSERT INTO postals VALUES (75, 4715, 36);                 
INSERT INTO postals VALUES (76, 4719, 36);                 
INSERT INTO postals VALUES (77, 4860, 37);                 
INSERT INTO postals VALUES (78, 4615, 38);                 
INSERT INTO postals VALUES (79, 4820, 38);                 
INSERT INTO postals VALUES (80, 4890, 38);                 
INSERT INTO postals VALUES (81, 4824, 39);                 
INSERT INTO postals VALUES (82, 4765, 40);                 
INSERT INTO postals VALUES (83, 4800, 40);                 
INSERT INTO postals VALUES (84, 4804, 40);                 
INSERT INTO postals VALUES (85, 4805, 40);                 
INSERT INTO postals VALUES (86, 4809, 40);                 
INSERT INTO postals VALUES (87, 4810, 40);                 
INSERT INTO postals VALUES (88, 4814, 40);                 
INSERT INTO postals VALUES (89, 4815, 40);                 
INSERT INTO postals VALUES (90, 4835, 40);                 
INSERT INTO postals VALUES (91, 4839, 40);                 
INSERT INTO postals VALUES (92, 4830, 41);                 
INSERT INTO postals VALUES (93, 4840, 42);                 
INSERT INTO postals VALUES (94, 4845, 42);                 
INSERT INTO postals VALUES (95, 4850, 43);                 
INSERT INTO postals VALUES (96, 4760, 44);                 
INSERT INTO postals VALUES (97, 4763, 44);                 
INSERT INTO postals VALUES (98, 4764, 44);                 
INSERT INTO postals VALUES (99, 4770, 44);                 
INSERT INTO postals VALUES (100, 5300, 44);                
INSERT INTO postals VALUES (101, 5301, 45);                
INSERT INTO postals VALUES (102, 5140, 46);                
INSERT INTO postals VALUES (103, 5180, 47);                
INSERT INTO postals VALUES (104, 5340, 48);                
INSERT INTO postals VALUES (105, 5210, 49);                
INSERT INTO postals VALUES (106, 5225, 49);                
INSERT INTO postals VALUES (107, 5370, 50);                
INSERT INTO postals VALUES (108, 5374, 50);                
INSERT INTO postals VALUES (109, 5385, 50);                
INSERT INTO postals VALUES (110, 5200, 51);                
INSERT INTO postals VALUES (111, 5204, 51);                
INSERT INTO postals VALUES (112, 5350, 51);                
INSERT INTO postals VALUES (113, 5160, 52);                
INSERT INTO postals VALUES (114, 5164, 52);                
INSERT INTO postals VALUES (115, 5360, 53);                
INSERT INTO postals VALUES (116, 5230, 54);                
INSERT INTO postals VALUES (117, 5320, 55);                
INSERT INTO postals VALUES (118, 5335, 55);                
INSERT INTO postals VALUES (119, 6250, 56);                
INSERT INTO postals VALUES (120, 6000, 57);                
INSERT INTO postals VALUES (121, 6004, 57);                
INSERT INTO postals VALUES (122, 6005, 57);                
INSERT INTO postals VALUES (123, 6200, 58);                
INSERT INTO postals VALUES (124, 6201, 58);                
INSERT INTO postals VALUES (125, 6215, 58);                
INSERT INTO postals VALUES (126, 6225, 58);                
INSERT INTO postals VALUES (127, 6230, 58);                
INSERT INTO postals VALUES (128, 6185, 59);                
INSERT INTO postals VALUES (129, 6060, 60);                
INSERT INTO postals VALUES (130, 6160, 61);                
INSERT INTO postals VALUES (131, 6090, 62);                
INSERT INTO postals VALUES (132, 6320, 62);                
INSERT INTO postals VALUES (133, 6150, 63);                
INSERT INTO postals VALUES (134, 6100, 64);                
INSERT INTO postals VALUES (135, 6110, 65);                
INSERT INTO postals VALUES (136, 6030, 66);                
INSERT INTO postals VALUES (137, 3300, 67);                
INSERT INTO postals VALUES (138, 3305, 67);                
INSERT INTO postals VALUES (139, 3060, 68);                
INSERT INTO postals VALUES (140, 3064, 68);                
INSERT INTO postals VALUES (141, 3000, 69);                
INSERT INTO postals VALUES (142, 3004, 69);                
INSERT INTO postals VALUES (143, 3025, 69);                
INSERT INTO postals VALUES (144, 3030, 69);                
INSERT INTO postals VALUES (145, 3034, 69);                
INSERT INTO postals VALUES (146, 3040, 69);                
INSERT INTO postals VALUES (147, 3044, 69);                
INSERT INTO postals VALUES (148, 3045, 69);                
INSERT INTO postals VALUES (149, 3048, 69);                
INSERT INTO postals VALUES (150, 3049, 69);                
INSERT INTO postals VALUES (151, 3150, 70);                
INSERT INTO postals VALUES (152, 3154, 70);                
INSERT INTO postals VALUES (153, 3080, 71);                
INSERT INTO postals VALUES (154, 3084, 71);                
INSERT INTO postals VALUES (155, 3090, 71);                
INSERT INTO postals VALUES (156, 3094, 71);                
INSERT INTO postals VALUES (157, 3330, 72);                
INSERT INTO postals VALUES (158, 3334, 72);                
INSERT INTO postals VALUES (159, 3200, 73);                
INSERT INTO postals VALUES (160, 3070, 74);                
INSERT INTO postals VALUES (161, 3074, 74);                
INSERT INTO postals VALUES (162, 3220, 75);                
INSERT INTO postals VALUES (163, 3140, 76);                
INSERT INTO postals VALUES (164, 3144, 76);                
INSERT INTO postals VALUES (165, 3400, 77);                
INSERT INTO postals VALUES (166, 3404, 77);                
INSERT INTO postals VALUES (167, 3405, 77);                
INSERT INTO postals VALUES (168, 3320, 78);                
INSERT INTO postals VALUES (169, 3360, 79);                
INSERT INTO postals VALUES (170, 3230, 80);                
INSERT INTO postals VALUES (171, 3234, 80);                
INSERT INTO postals VALUES (172, 3130, 81);                
INSERT INTO postals VALUES (173, 3420, 82);                
INSERT INTO postals VALUES (174, 3350, 83);                
INSERT INTO postals VALUES (175, 7200, 84);                
INSERT INTO postals VALUES (176, 7250, 84);                
INSERT INTO postals VALUES (177, 7040, 85);                
INSERT INTO postals VALUES (178, 7150, 86);                
INSERT INTO postals VALUES (179, 7154, 86);                
INSERT INTO postals VALUES (180, 7100, 87);                
INSERT INTO postals VALUES (181, 7000, 88);                
INSERT INTO postals VALUES (182, 7004, 88);                
INSERT INTO postals VALUES (183, 7005, 88);                
INSERT INTO postals VALUES (184, 7009, 88);                
INSERT INTO postals VALUES (185, 7050, 89);                
INSERT INTO postals VALUES (186, 7490, 90);                
INSERT INTO postals VALUES (187, 7240, 91);                
INSERT INTO postals VALUES (188, 7244, 91);                
INSERT INTO postals VALUES (189, 7220, 92);                
INSERT INTO postals VALUES (190, 7170, 93);                
INSERT INTO postals VALUES (191, 2965, 94);                
INSERT INTO postals VALUES (192, 7080, 94);                
INSERT INTO postals VALUES (193, 7090, 95);                
INSERT INTO postals VALUES (194, 7160, 96);                
INSERT INTO postals VALUES (195, 8200, 97);                
INSERT INTO postals VALUES (196, 8201, 97);                
INSERT INTO postals VALUES (197, 8203, 97);                
INSERT INTO postals VALUES (198, 8970, 98);                
INSERT INTO postals VALUES (199, 8670, 99);                
INSERT INTO postals VALUES (200, 8950, 100);               
INSERT INTO postals VALUES (201, 8954, 100);               
INSERT INTO postals VALUES (202, 8000, 101);               
INSERT INTO postals VALUES (203, 8004, 101);               
INSERT INTO postals VALUES (204, 8005, 101);               
INSERT INTO postals VALUES (205, 8009, 101);               
INSERT INTO postals VALUES (206, 8400, 102);               
INSERT INTO postals VALUES (207, 8401, 102);               
INSERT INTO postals VALUES (208, 8600, 103);               
INSERT INTO postals VALUES (209, 8601, 103);               
INSERT INTO postals VALUES (210, 8604, 103);               
INSERT INTO postals VALUES (211, 8100, 104);               
INSERT INTO postals VALUES (212, 8104, 104);               
INSERT INTO postals VALUES (213, 8125, 104);               
INSERT INTO postals VALUES (214, 8135, 104);               
INSERT INTO postals VALUES (215, 8136, 104);               
INSERT INTO postals VALUES (216, 8550, 105);               
INSERT INTO postals VALUES (217, 8700, 106);               
INSERT INTO postals VALUES (218, 8500, 107);               
INSERT INTO postals VALUES (219, 8501, 107);               
INSERT INTO postals VALUES (220, 8150, 108);               
INSERT INTO postals VALUES (221, 8300, 109);               
INSERT INTO postals VALUES (222, 8365, 109);               
INSERT INTO postals VALUES (223, 8375, 109);               
INSERT INTO postals VALUES (224, 8800, 110);               
INSERT INTO postals VALUES (225, 8801, 110);               
INSERT INTO postals VALUES (226, 8804, 110);               
INSERT INTO postals VALUES (227, 8650, 111);               
INSERT INTO postals VALUES (228, 8900, 112);               
INSERT INTO postals VALUES (229, 3570, 113);               
INSERT INTO postals VALUES (230, 6350, 114);               
INSERT INTO postals VALUES (231, 6355, 114);               
INSERT INTO postals VALUES (232, 6360, 115);               
INSERT INTO postals VALUES (233, 6440, 116);               
INSERT INTO postals VALUES (234, 6370, 117);               
INSERT INTO postals VALUES (235, 6290, 118);               
INSERT INTO postals VALUES (236, 6294, 118);               
INSERT INTO postals VALUES (237, 6300, 119);               
INSERT INTO postals VALUES (238, 6301, 119);               
INSERT INTO postals VALUES (239, 6260, 120);               
INSERT INTO postals VALUES (240, 6264, 120);               
INSERT INTO postals VALUES (241, 6430, 121);               
INSERT INTO postals VALUES (242, 6400, 122);               
INSERT INTO postals VALUES (243, 6324, 123);               
INSERT INTO postals VALUES (244, 6270, 124);               
INSERT INTO postals VALUES (245, 6274, 124);               
INSERT INTO postals VALUES (246, 6285, 124);               
INSERT INTO postals VALUES (247, 3640, 125);               
INSERT INTO postals VALUES (248, 6420, 125);               
INSERT INTO postals VALUES (249, 6424, 125);               
INSERT INTO postals VALUES (250, 5150, 126);               
INSERT INTO postals VALUES (251, 5155, 126);               
INSERT INTO postals VALUES (252, 2445, 127);               
INSERT INTO postals VALUES (253, 2460, 127);               
INSERT INTO postals VALUES (254, 2461, 127);               
INSERT INTO postals VALUES (255, 2475, 127);               
INSERT INTO postals VALUES (256, 3250, 128);               
INSERT INTO postals VALUES (257, 3254, 128);               
INSERT INTO postals VALUES (258, 3260, 128);               
INSERT INTO postals VALUES (259, 3240, 129);               
INSERT INTO postals VALUES (260, 2440, 130);               
INSERT INTO postals VALUES (261, 2495, 130);               
INSERT INTO postals VALUES (262, 2540, 131);               
INSERT INTO postals VALUES (263, 2500, 132);               
INSERT INTO postals VALUES (264, 2504, 132);               
INSERT INTO postals VALUES (265, 3280, 133);               
INSERT INTO postals VALUES (266, 2400, 134);               
INSERT INTO postals VALUES (267, 2404, 134);               
INSERT INTO postals VALUES (268, 2405, 134);               
INSERT INTO postals VALUES (269, 2410, 134);               
INSERT INTO postals VALUES (270, 2414, 134);               
INSERT INTO postals VALUES (271, 2415, 134);               
INSERT INTO postals VALUES (272, 2419, 134);               
INSERT INTO postals VALUES (273, 2420, 134);               
INSERT INTO postals VALUES (274, 2423, 134);               
INSERT INTO postals VALUES (275, 2424, 134);               
INSERT INTO postals VALUES (276, 2425, 134);               
INSERT INTO postals VALUES (277, 2499, 134);               
INSERT INTO postals VALUES (278, 2430, 135);               
INSERT INTO postals VALUES (279, 2434, 135);               
INSERT INTO postals VALUES (280, 2450, 136);               
INSERT INTO postals VALUES (281, 2510, 137);               
INSERT INTO postals VALUES (282, 3270, 138);               
INSERT INTO postals VALUES (283, 2520, 139);               
INSERT INTO postals VALUES (284, 2525, 139);               
INSERT INTO postals VALUES (285, 3100, 140);               
INSERT INTO postals VALUES (286, 3104, 140);               
INSERT INTO postals VALUES (287, 3105, 140);               
INSERT INTO postals VALUES (288, 2480, 141);               
INSERT INTO postals VALUES (289, 2484, 141);               
INSERT INTO postals VALUES (290, 2485, 141);               
INSERT INTO postals VALUES (291, 2580, 142);               
INSERT INTO postals VALUES (292, 2581, 142);               
INSERT INTO postals VALUES (293, 2630, 143);               
INSERT INTO postals VALUES (294, 2634, 143);               
INSERT INTO postals VALUES (295, 2050, 144);               
INSERT INTO postals VALUES (296, 2054, 144);               
INSERT INTO postals VALUES (297, 2065, 144);               
INSERT INTO postals VALUES (298, 2550, 145);               
INSERT INTO postals VALUES (299, 2645, 146);               
INSERT INTO postals VALUES (300, 2649, 146);               
INSERT INTO postals VALUES (301, 2750, 146);               
INSERT INTO postals VALUES (302, 2754, 146);               
INSERT INTO postals VALUES (303, 2755, 146);               
INSERT INTO postals VALUES (304, 2756, 146);               
INSERT INTO postals VALUES (305, 2765, 146);               
INSERT INTO postals VALUES (306, 2769, 146);               
INSERT INTO postals VALUES (307, 2775, 146);               
INSERT INTO postals VALUES (308, 2779, 146);               
INSERT INTO postals VALUES (309, 2785, 146);               
INSERT INTO postals VALUES (310, 2789, 146);               
INSERT INTO postals VALUES (311, 1000, 147);               
INSERT INTO postals VALUES (312, 1049, 147);               
INSERT INTO postals VALUES (313, 1050, 147);               
INSERT INTO postals VALUES (314, 1067, 147);               
INSERT INTO postals VALUES (315, 1068, 147);               
INSERT INTO postals VALUES (316, 1069, 147);               
INSERT INTO postals VALUES (317, 1070, 147);               
INSERT INTO postals VALUES (318, 1098, 147);               
INSERT INTO postals VALUES (319, 1099, 147);               
INSERT INTO postals VALUES (320, 1100, 147);               
INSERT INTO postals VALUES (321, 1149, 147);               
INSERT INTO postals VALUES (322, 1150, 147);               
INSERT INTO postals VALUES (323, 1169, 147);               
INSERT INTO postals VALUES (324, 1170, 147);               
INSERT INTO postals VALUES (325, 1199, 147);               
INSERT INTO postals VALUES (326, 1200, 147);               
INSERT INTO postals VALUES (327, 1249, 147);               
INSERT INTO postals VALUES (328, 1250, 147);               
INSERT INTO postals VALUES (329, 1269, 147);               
INSERT INTO postals VALUES (330, 1300, 147);               
INSERT INTO postals VALUES (331, 1349, 147);               
INSERT INTO postals VALUES (332, 1350, 147);               
INSERT INTO postals VALUES (333, 1399, 147);               
INSERT INTO postals VALUES (334, 1400, 147);               
INSERT INTO postals VALUES (335, 1449, 147);               
INSERT INTO postals VALUES (336, 1500, 147);               
INSERT INTO postals VALUES (337, 1549, 147);               
INSERT INTO postals VALUES (338, 1600, 147);               
INSERT INTO postals VALUES (339, 1649, 147);               
INSERT INTO postals VALUES (340, 1700, 147);               
INSERT INTO postals VALUES (341, 1748, 147);               
INSERT INTO postals VALUES (342, 1749, 147);               
INSERT INTO postals VALUES (343, 1750, 147);               
INSERT INTO postals VALUES (344, 1769, 147);               
INSERT INTO postals VALUES (345, 1800, 147);               
INSERT INTO postals VALUES (346, 1849, 147);               
INSERT INTO postals VALUES (347, 1900, 147);               
INSERT INTO postals VALUES (348, 1949, 147);               
INSERT INTO postals VALUES (349, 1950, 147);               
INSERT INTO postals VALUES (350, 1959, 147);               
INSERT INTO postals VALUES (351, 1990, 147);               
INSERT INTO postals VALUES (352, 1998, 147);               
INSERT INTO postals VALUES (353, 1999, 147);               
INSERT INTO postals VALUES (354, 1885, 148);               
INSERT INTO postals VALUES (355, 1886, 148);               
INSERT INTO postals VALUES (356, 2660, 148);               
INSERT INTO postals VALUES (357, 2664, 148);               
INSERT INTO postals VALUES (358, 2670, 148);               
INSERT INTO postals VALUES (359, 2674, 148);               
INSERT INTO postals VALUES (360, 2680, 148);               
INSERT INTO postals VALUES (361, 2681, 148);               
INSERT INTO postals VALUES (362, 2685, 148);               
INSERT INTO postals VALUES (363, 2688, 148);               
INSERT INTO postals VALUES (364, 2689, 148);               
INSERT INTO postals VALUES (365, 2690, 148);               
INSERT INTO postals VALUES (366, 2691, 148);               
INSERT INTO postals VALUES (367, 2694, 148);               
INSERT INTO postals VALUES (368, 2695, 148);               
INSERT INTO postals VALUES (369, 2699, 148);               
INSERT INTO postals VALUES (370, 2530, 149);               
INSERT INTO postals VALUES (371, 2640, 150);               
INSERT INTO postals VALUES (372, 2644, 150);               
INSERT INTO postals VALUES (373, 2655, 150);               
INSERT INTO postals VALUES (374, 2659, 150);               
INSERT INTO postals VALUES (375, 2665, 150);               
INSERT INTO postals VALUES (376, 2669, 150);               
INSERT INTO postals VALUES (377, 1495, 151);               
INSERT INTO postals VALUES (378, 1499, 151);               
INSERT INTO postals VALUES (379, 2730, 151);               
INSERT INTO postals VALUES (380, 2734, 151);               
INSERT INTO postals VALUES (381, 2740, 151);               
INSERT INTO postals VALUES (382, 2744, 151);               
INSERT INTO postals VALUES (383, 2760, 151);               
INSERT INTO postals VALUES (384, 2761, 151);               
INSERT INTO postals VALUES (385, 2770, 151);               
INSERT INTO postals VALUES (386, 2774, 151);               
INSERT INTO postals VALUES (387, 2780, 151);               
INSERT INTO postals VALUES (388, 2784, 151);               
INSERT INTO postals VALUES (389, 2790, 151);               
INSERT INTO postals VALUES (390, 2794, 151);               
INSERT INTO postals VALUES (391, 2795, 151);               
INSERT INTO postals VALUES (392, 2799, 151);               
INSERT INTO postals VALUES (393, 2605, 152);               
INSERT INTO postals VALUES (394, 2609, 152);               
INSERT INTO postals VALUES (395, 2635, 152);               
INSERT INTO postals VALUES (396, 2639, 152);               
INSERT INTO postals VALUES (397, 2705, 152);               
INSERT INTO postals VALUES (398, 2706, 152);               
INSERT INTO postals VALUES (399, 2709, 152);               
INSERT INTO postals VALUES (400, 2710, 152);               
INSERT INTO postals VALUES (401, 2714, 152);               
INSERT INTO postals VALUES (402, 2715, 152);               
INSERT INTO postals VALUES (403, 2718, 152);               
INSERT INTO postals VALUES (404, 2719, 152);               
INSERT INTO postals VALUES (405, 2725, 152);               
INSERT INTO postals VALUES (406, 2729, 152);               
INSERT INTO postals VALUES (407, 2735, 152);               
INSERT INTO postals VALUES (408, 2739, 152);               
INSERT INTO postals VALUES (409, 2745, 152);               
INSERT INTO postals VALUES (410, 2749, 152);               
INSERT INTO postals VALUES (411, 2590, 153);               
INSERT INTO postals VALUES (412, 2594, 153);               
INSERT INTO postals VALUES (413, 2560, 154);               
INSERT INTO postals VALUES (414, 2565, 154);               
INSERT INTO postals VALUES (415, 2600, 155);               
INSERT INTO postals VALUES (416, 2601, 155);               
INSERT INTO postals VALUES (417, 2615, 155);               
INSERT INTO postals VALUES (418, 2619, 155);               
INSERT INTO postals VALUES (419, 2625, 155);               
INSERT INTO postals VALUES (420, 2626, 155);               
INSERT INTO postals VALUES (421, 2628, 155);               
INSERT INTO postals VALUES (422, 2629, 155);               
INSERT INTO postals VALUES (423, 2610, 156);               
INSERT INTO postals VALUES (424, 2614, 156);               
INSERT INTO postals VALUES (425, 2650, 156);               
INSERT INTO postals VALUES (426, 2654, 156);               
INSERT INTO postals VALUES (427, 2700, 156);               
INSERT INTO postals VALUES (428, 2704, 156);               
INSERT INTO postals VALUES (429, 2720, 156);               
INSERT INTO postals VALUES (430, 2724, 156);               
INSERT INTO postals VALUES (431, 1675, 157);               
INSERT INTO postals VALUES (432, 1679, 157);               
INSERT INTO postals VALUES (433, 1685, 157);               
INSERT INTO postals VALUES (434, 1689, 157);               
INSERT INTO postals VALUES (435, 2620, 157);               
INSERT INTO postals VALUES (436, 2621, 157);               
INSERT INTO postals VALUES (437, 2675, 157);               
INSERT INTO postals VALUES (438, 7440, 158);               
INSERT INTO postals VALUES (439, 7340, 159);               
INSERT INTO postals VALUES (440, 7480, 160);               
INSERT INTO postals VALUES (441, 7370, 161);               
INSERT INTO postals VALUES (442, 7374, 161);               
INSERT INTO postals VALUES (443, 7320, 162);               
INSERT INTO postals VALUES (444, 7430, 163);               
INSERT INTO postals VALUES (445, 7350, 164);               
INSERT INTO postals VALUES (446, 7354, 164);               
INSERT INTO postals VALUES (447, 7460, 165);               
INSERT INTO postals VALUES (448, 7464, 165);               
INSERT INTO postals VALUES (449, 6040, 166);               
INSERT INTO postals VALUES (450, 7330, 167);               
INSERT INTO postals VALUES (451, 7450, 168);               
INSERT INTO postals VALUES (452, 6050, 169);               
INSERT INTO postals VALUES (453, 7400, 170);               
INSERT INTO postals VALUES (454, 7425, 170);               
INSERT INTO postals VALUES (455, 7300, 171);               
INSERT INTO postals VALUES (456, 7301, 171);               
INSERT INTO postals VALUES (457, 7470, 172);               
INSERT INTO postals VALUES (458, 4600, 173);               
INSERT INTO postals VALUES (459, 4605, 173);               
INSERT INTO postals VALUES (460, 4640, 174);               
INSERT INTO postals VALUES (461, 5040, 174);               
INSERT INTO postals VALUES (462, 4610, 175);               
INSERT INTO postals VALUES (463, 4614, 175);               
INSERT INTO postals VALUES (464, 4650, 175);               
INSERT INTO postals VALUES (465, 4420, 176);               
INSERT INTO postals VALUES (466, 4424, 176);               
INSERT INTO postals VALUES (467, 4435, 176);               
INSERT INTO postals VALUES (468, 4510, 176);               
INSERT INTO postals VALUES (469, 4515, 176);               
INSERT INTO postals VALUES (470, 4620, 177);               
INSERT INTO postals VALUES (471, 4624, 177);               
INSERT INTO postals VALUES (472, 4425, 178);               
INSERT INTO postals VALUES (473, 4470, 178);               
INSERT INTO postals VALUES (474, 4474, 178);               
INSERT INTO postals VALUES (475, 4475, 178);               
INSERT INTO postals VALUES (476, 4477, 178);               
INSERT INTO postals VALUES (477, 4479, 178);               
INSERT INTO postals VALUES (478, 4575, 179);               
INSERT INTO postals VALUES (479, 4625, 179);               
INSERT INTO postals VALUES (480, 4630, 179);               
INSERT INTO postals VALUES (481, 4634, 179);               
INSERT INTO postals VALUES (482, 4635, 179);               
INSERT INTO postals VALUES (483, 4450, 180);               
INSERT INTO postals VALUES (484, 4454, 180);               
INSERT INTO postals VALUES (485, 4455, 180);               
INSERT INTO postals VALUES (486, 4458, 180);               
INSERT INTO postals VALUES (487, 4459, 180);               
INSERT INTO postals VALUES (488, 4460, 180);               
INSERT INTO postals VALUES (489, 4464, 180);               
INSERT INTO postals VALUES (490, 4465, 180);               
INSERT INTO postals VALUES (491, 4590, 181);               
INSERT INTO postals VALUES (492, 4594, 181);               
INSERT INTO postals VALUES (493, 4595, 181);               
INSERT INTO postals VALUES (494, 4599, 181);               
INSERT INTO postals VALUES (495, 4580, 182);               
INSERT INTO postals VALUES (496, 4585, 182);               
INSERT INTO postals VALUES (497, 4560, 183);               
INSERT INTO postals VALUES (498, 4564, 183);               
INSERT INTO postals VALUES (499, 4000, 184);               
INSERT INTO postals VALUES (500, 4049, 184);               
INSERT INTO postals VALUES (501, 4050, 184);               
INSERT INTO postals VALUES (502, 4099, 184);               
INSERT INTO postals VALUES (503, 4100, 184);               
INSERT INTO postals VALUES (504, 4149, 184);               
INSERT INTO postals VALUES (505, 4150, 184);               
INSERT INTO postals VALUES (506, 4169, 184);               
INSERT INTO postals VALUES (507, 4199, 184);               
INSERT INTO postals VALUES (508, 4200, 184);               
INSERT INTO postals VALUES (509, 4249, 184);               
INSERT INTO postals VALUES (510, 4250, 184);               
INSERT INTO postals VALUES (511, 4269, 184);               
INSERT INTO postals VALUES (512, 4300, 184);               
INSERT INTO postals VALUES (513, 4349, 184);               
INSERT INTO postals VALUES (514, 4350, 184);               
INSERT INTO postals VALUES (515, 4369, 184);               
INSERT INTO postals VALUES (516, 4490, 185);               
INSERT INTO postals VALUES (517, 4495, 185);               
INSERT INTO postals VALUES (518, 4570, 185);               
INSERT INTO postals VALUES (519, 4780, 186);               
INSERT INTO postals VALUES (520, 4795, 186);               
INSERT INTO postals VALUES (521, 4825, 186);               
INSERT INTO postals VALUES (522, 4440, 187);               
INSERT INTO postals VALUES (523, 4445, 187);               
INSERT INTO postals VALUES (524, 4480, 188);               
INSERT INTO postals VALUES (525, 4484, 188);               
INSERT INTO postals VALUES (526, 4485, 188);               
INSERT INTO postals VALUES (527, 4486, 188);               
INSERT INTO postals VALUES (528, 4400, 189);               
INSERT INTO postals VALUES (529, 4404, 189);               
INSERT INTO postals VALUES (530, 4405, 189);               
INSERT INTO postals VALUES (531, 4409, 189);               
INSERT INTO postals VALUES (532, 4410, 189);               
INSERT INTO postals VALUES (533, 4414, 189);               
INSERT INTO postals VALUES (534, 4415, 189);               
INSERT INTO postals VALUES (535, 4430, 189);               
INSERT INTO postals VALUES (536, 4434, 189);               
INSERT INTO postals VALUES (537, 4745, 190);               
INSERT INTO postals VALUES (538, 4785, 190);               
INSERT INTO postals VALUES (539, 2200, 191);               
INSERT INTO postals VALUES (540, 2205, 191);               
INSERT INTO postals VALUES (541, 2230, 191);               
INSERT INTO postals VALUES (542, 2380, 192);               
INSERT INTO postals VALUES (543, 2384, 192);               
INSERT INTO postals VALUES (544, 2395, 192);               
INSERT INTO postals VALUES (545, 2080, 193);               
INSERT INTO postals VALUES (546, 2090, 194);               
INSERT INTO postals VALUES (547, 2130, 195);               
INSERT INTO postals VALUES (548, 2135, 195);               
INSERT INTO postals VALUES (549, 2139, 195);               
INSERT INTO postals VALUES (550, 2890, 195);               
INSERT INTO postals VALUES (551, 2070, 196);               
INSERT INTO postals VALUES (552, 2140, 197);               
INSERT INTO postals VALUES (553, 2250, 198);               
INSERT INTO postals VALUES (554, 2100, 199);               
INSERT INTO postals VALUES (555, 2330, 200);               
INSERT INTO postals VALUES (556, 2334, 200);               
INSERT INTO postals VALUES (557, 2240, 201);               
INSERT INTO postals VALUES (558, 2150, 202);               
INSERT INTO postals VALUES (559, 2154, 202);               
INSERT INTO postals VALUES (560, 6120, 203);               
INSERT INTO postals VALUES (561, 2040, 204);               
INSERT INTO postals VALUES (562, 2120, 205);               
INSERT INTO postals VALUES (563, 2124, 205);               
INSERT INTO postals VALUES (564, 2125, 205);               
INSERT INTO postals VALUES (565, 2000, 206);               
INSERT INTO postals VALUES (566, 2004, 206);               
INSERT INTO postals VALUES (567, 2005, 206);               
INSERT INTO postals VALUES (568, 2009, 206);               
INSERT INTO postals VALUES (569, 2025, 206);               
INSERT INTO postals VALUES (570, 2300, 207);               
INSERT INTO postals VALUES (571, 2305, 207);               
INSERT INTO postals VALUES (572, 2350, 208);               
INSERT INTO postals VALUES (573, 2260, 209);               
INSERT INTO postals VALUES (574, 2435, 210);               
INSERT INTO postals VALUES (575, 2490, 210);               
INSERT INTO postals VALUES (576, 7580, 211);               
INSERT INTO postals VALUES (577, 7595, 211);               
INSERT INTO postals VALUES (578, 2894, 212);               
INSERT INTO postals VALUES (579, 2800, 213);               
INSERT INTO postals VALUES (580, 2804, 213);               
INSERT INTO postals VALUES (581, 2805, 213);               
INSERT INTO postals VALUES (582, 2809, 213);               
INSERT INTO postals VALUES (583, 2810, 213);               
INSERT INTO postals VALUES (584, 2814, 213);               
INSERT INTO postals VALUES (585, 2815, 213);               
INSERT INTO postals VALUES (586, 2819, 213);               
INSERT INTO postals VALUES (587, 2820, 213);               
INSERT INTO postals VALUES (588, 2821, 213);               
INSERT INTO postals VALUES (589, 2825, 213);               
INSERT INTO postals VALUES (590, 2829, 213);               
INSERT INTO postals VALUES (591, 2830, 214);               
INSERT INTO postals VALUES (592, 2834, 214);               
INSERT INTO postals VALUES (593, 2835, 214);               
INSERT INTO postals VALUES (594, 2839, 214);               
INSERT INTO postals VALUES (595, 7570, 215);               
INSERT INTO postals VALUES (596, 2860, 216);               
INSERT INTO postals VALUES (597, 2864, 216);               
INSERT INTO postals VALUES (598, 2870, 217);               
INSERT INTO postals VALUES (599, 2985, 217);               
INSERT INTO postals VALUES (600, 2950, 218);               
INSERT INTO postals VALUES (601, 2951, 218);               
INSERT INTO postals VALUES (602, 2954, 218);               
INSERT INTO postals VALUES (603, 2955, 218);               
INSERT INTO postals VALUES (604, 2959, 218);               
INSERT INTO postals VALUES (605, 7500, 219);               
INSERT INTO postals VALUES (606, 7540, 219);               
INSERT INTO postals VALUES (607, 7555, 219);               
INSERT INTO postals VALUES (608, 7565, 219);               
INSERT INTO postals VALUES (609, 2840, 220);               
INSERT INTO postals VALUES (610, 2844, 220);               
INSERT INTO postals VALUES (611, 2845, 220);               
INSERT INTO postals VALUES (612, 2848, 220);               
INSERT INTO postals VALUES (613, 2855, 220);               
INSERT INTO postals VALUES (614, 2865, 220);               
INSERT INTO postals VALUES (615, 2970, 221);               
INSERT INTO postals VALUES (616, 2975, 221);               
INSERT INTO postals VALUES (617, 2900, 222);               
INSERT INTO postals VALUES (618, 2904, 222);               
INSERT INTO postals VALUES (619, 2910, 222);               
INSERT INTO postals VALUES (620, 2914, 222);               
INSERT INTO postals VALUES (621, 2925, 222);               
INSERT INTO postals VALUES (622, 2929, 222);               
INSERT INTO postals VALUES (623, 7520, 223);               
INSERT INTO postals VALUES (624, 4970, 224);               
INSERT INTO postals VALUES (625, 4974, 224);               
INSERT INTO postals VALUES (626, 4910, 225);               
INSERT INTO postals VALUES (627, 4914, 225);               
INSERT INTO postals VALUES (628, 4960, 226);               
INSERT INTO postals VALUES (629, 4964, 226);               
INSERT INTO postals VALUES (630, 4950, 227);               
INSERT INTO postals VALUES (631, 4940, 228);               
INSERT INTO postals VALUES (632, 4944, 228);               
INSERT INTO postals VALUES (633, 4980, 229);               
INSERT INTO postals VALUES (634, 4990, 230);               
INSERT INTO postals VALUES (635, 4930, 231);               
INSERT INTO postals VALUES (636, 4900, 232);               
INSERT INTO postals VALUES (637, 4904, 232);               
INSERT INTO postals VALUES (638, 4925, 232);               
INSERT INTO postals VALUES (639, 4935, 232);               
INSERT INTO postals VALUES (640, 4939, 232);               
INSERT INTO postals VALUES (641, 4920, 233);               
INSERT INTO postals VALUES (642, 4924, 233);               
INSERT INTO postals VALUES (643, 5070, 234);               
INSERT INTO postals VALUES (644, 5085, 234);               
INSERT INTO postals VALUES (645, 5460, 235);               
INSERT INTO postals VALUES (646, 5400, 236);               
INSERT INTO postals VALUES (647, 5425, 236);               
INSERT INTO postals VALUES (648, 4880, 237);               
INSERT INTO postals VALUES (649, 5470, 238);               
INSERT INTO postals VALUES (650, 5090, 239);               
INSERT INTO postals VALUES (651, 5050, 240);               
INSERT INTO postals VALUES (652, 5054, 240);               
INSERT INTO postals VALUES (653, 4870, 241);               
INSERT INTO postals VALUES (654, 5060, 242);               
INSERT INTO postals VALUES (655, 5030, 243);               
INSERT INTO postals VALUES (656, 5430, 244);               
INSERT INTO postals VALUES (657, 5445, 244);               
INSERT INTO postals VALUES (658, 5450, 245);               
INSERT INTO postals VALUES (659, 5000, 246);               
INSERT INTO postals VALUES (660, 5004, 246);               
INSERT INTO postals VALUES (661, 5110, 247);               
INSERT INTO postals VALUES (662, 5114, 247);               
INSERT INTO postals VALUES (663, 3430, 248);               
INSERT INTO postals VALUES (664, 3600, 249);               
INSERT INTO postals VALUES (665, 4690, 250);               
INSERT INTO postals VALUES (666, 5100, 251);               
INSERT INTO postals VALUES (667, 3530, 252);               
INSERT INTO postals VALUES (668, 3534, 252);               
INSERT INTO postals VALUES (669, 3620, 253);               
INSERT INTO postals VALUES (670, 3624, 253);               
INSERT INTO postals VALUES (671, 3450, 254);               
INSERT INTO postals VALUES (672, 3520, 255);               
INSERT INTO postals VALUES (673, 3525, 255);               
INSERT INTO postals VALUES (674, 3475, 256);               
INSERT INTO postals VALUES (675, 3680, 256);               
INSERT INTO postals VALUES (676, 3550, 257);               
INSERT INTO postals VALUES (677, 3630, 258);               
INSERT INTO postals VALUES (678, 4660, 259);               
INSERT INTO postals VALUES (679, 4664, 259);               
INSERT INTO postals VALUES (680, 3440, 260);               
INSERT INTO postals VALUES (681, 5130, 261);               
INSERT INTO postals VALUES (682, 3660, 262);               
INSERT INTO postals VALUES (683, 3560, 263);               
INSERT INTO postals VALUES (684, 3650, 263);               
INSERT INTO postals VALUES (685, 5120, 264);               
INSERT INTO postals VALUES (686, 3610, 265);               
INSERT INTO postals VALUES (687, 3460, 266);               
INSERT INTO postals VALUES (688, 3464, 266);               
INSERT INTO postals VALUES (689, 3465, 266);               
INSERT INTO postals VALUES (690, 3654, 267);               
INSERT INTO postals VALUES (691, 3500, 268);               
INSERT INTO postals VALUES (692, 3503, 268);               
INSERT INTO postals VALUES (693, 3504, 268);               
INSERT INTO postals VALUES (694, 3505, 268);               
INSERT INTO postals VALUES (695, 3510, 268);               
INSERT INTO postals VALUES (696, 3514, 268);               
INSERT INTO postals VALUES (697, 3515, 268);               
INSERT INTO postals VALUES (698, 3519, 268);               
INSERT INTO postals VALUES (699, 3670, 269);               
INSERT INTO postals VALUES (700, 9370, 270);               
INSERT INTO postals VALUES (701, 9374, 270);               
INSERT INTO postals VALUES (702, 9385, 270);               
INSERT INTO postals VALUES (703, 9030, 271);               
INSERT INTO postals VALUES (704, 9300, 271);               
INSERT INTO postals VALUES (705, 9304, 271);               
INSERT INTO postals VALUES (706, 9325, 271);               
INSERT INTO postals VALUES (707, 9000, 272);               
INSERT INTO postals VALUES (708, 9004, 272);               
INSERT INTO postals VALUES (709, 9020, 272);               
INSERT INTO postals VALUES (710, 9024, 272);               
INSERT INTO postals VALUES (711, 9050, 272);               
INSERT INTO postals VALUES (712, 9054, 272);               
INSERT INTO postals VALUES (713, 9060, 272);               
INSERT INTO postals VALUES (714, 9064, 272);               
INSERT INTO postals VALUES (715, 9200, 273);               
INSERT INTO postals VALUES (716, 9225, 273);               
INSERT INTO postals VALUES (717, 9360, 274);               
INSERT INTO postals VALUES (718, 9270, 275);               
INSERT INTO postals VALUES (719, 9350, 276);               
INSERT INTO postals VALUES (720, 9100, 277);               
INSERT INTO postals VALUES (721, 9125, 277);               
INSERT INTO postals VALUES (722, 9135, 277);               
INSERT INTO postals VALUES (723, 9230, 278);               
INSERT INTO postals VALUES (724, 9240, 279);               
INSERT INTO postals VALUES (725, 9400, 280);               
INSERT INTO postals VALUES (726, 9580, 281);               
INSERT INTO postals VALUES (727, 9560, 282);               
INSERT INTO postals VALUES (728, 9630, 283);               
INSERT INTO postals VALUES (729, 9500, 284);               
INSERT INTO postals VALUES (730, 9504, 284);               
INSERT INTO postals VALUES (731, 9545, 284);               
INSERT INTO postals VALUES (732, 9555, 284);               
INSERT INTO postals VALUES (733, 9650, 285);               
INSERT INTO postals VALUES (734, 9675, 285);               
INSERT INTO postals VALUES (735, 9600, 286);               
INSERT INTO postals VALUES (736, 9625, 286);               
INSERT INTO postals VALUES (737, 9680, 287);               
INSERT INTO postals VALUES (738, 9684, 287);               
INSERT INTO postals VALUES (739, 9700, 288);               
INSERT INTO postals VALUES (740, 9701, 288);               
INSERT INTO postals VALUES (741, 9760, 289);               
INSERT INTO postals VALUES (742, 9880, 290);               
INSERT INTO postals VALUES (743, 9850, 291);               
INSERT INTO postals VALUES (744, 9875, 291);               
INSERT INTO postals VALUES (745, 9800, 292);               
INSERT INTO postals VALUES (746, 9804, 292);               
INSERT INTO postals VALUES (747, 9930, 293);               
INSERT INTO postals VALUES (748, 9934, 293);               
INSERT INTO postals VALUES (749, 9950, 294);               
INSERT INTO postals VALUES (750, 9940, 295);               
INSERT INTO postals VALUES (751, 9944, 295);               
INSERT INTO postals VALUES (752, 9900, 296);               
INSERT INTO postals VALUES (753, 9901, 296);               
INSERT INTO postals VALUES (754, 9904, 296);               
INSERT INTO postals VALUES (755, 9960, 297);               
INSERT INTO postals VALUES (756, 9970, 298);               
INSERT INTO postals VALUES (757, 9980, 299);               
	

INSERT INTO admins (id_admin, username, email, name, password, photo, description, date_register, state_user) VALUES (1, 'kmaidlow0', 'kmaidlow0@prlog.org', 'Kalli Maidlow', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/aliquidenimvitae.png?size=50x50&set=set1', 'Innovative stable utilisation', '2020-03-30', 'active');
INSERT INTO admins (id_admin, username, email, name, password, photo, description, date_register, state_user) VALUES (2, 'waingel1', 'waingel1@blogs.com', 'Wendie Aingel', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/officiaautvoluptatem.jpg?size=50x50&set=set1', 'Organized grid-enabled database', '2020-03-25', 'suspended');
INSERT INTO admins (id_admin, username, email, name, password, photo, description, date_register, state_user) VALUES (3, 'pmowsley2', 'pmowsley2@adobe.com', 'Peterus Mowsley', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quibusdamestvelit.png?size=50x50&set=set1', 'Function-based reciprocal concept', '2020-05-04', 'active');
INSERT INTO admins (id_admin, username, email, name, password, photo, description, date_register, state_user) VALUES (4, 'rjosselsohn3', 'rjosselsohn3@mlb.com', 'Reagan Josselsohn', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/ullamaperiamoccaecati.jpg?size=50x50&set=set1', 'Intuitive holistic pricing structure', '2020-03-27', 'banned');
INSERT INTO admins (id_admin, username, email, name, password, photo, description, date_register, state_user) VALUES (5, 'bdawton4', 'bdawton4@joomla.org', 'Barney Dawton', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/nobissintquos.jpg?size=50x50&set=set1', 'Business-focused bifurcated access', '2020-03-10', 'active');
INSERT INTO admins (id_admin, username, email, name, password, photo, description, date_register, state_user) VALUES (6, 'osongust5', 'osongust5@homestead.com', 'Ole Songust', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/hicipsumaut.bmp?size=50x50&set=set1', 'Team-oriented clear-thinking capacity', '2020-03-15', 'active');
INSERT INTO admins (id_admin, username, email, name, password, photo, description, date_register, state_user) VALUES (7, 'admin', 'admin@ebaw.pt', 'admin','$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS',NULL, 'die Meister', '2020-05-27', 'active');

INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'johndoe0', 'john@example.com', 'John Doe', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/laudantiuminquidem.bmp?size=50x50&set=set1', 'Horizontal', '2020-03-30', 'active', 919876543, '90 Surrey Trail', 47, '1991-09-15', 63);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'nduester1', 'nduester1@storify.com', 'Nissie Duester', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/enimipsumquis.bmp?size=50x50&set=set1', 'Realigned', '2020-03-14', 'banned', 919876543, '02737 Darwin Park', 75, '1981-01-09', 38);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cleil2', 'cleil2@indiegogo.com', 'Culver Leil', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/teneturnemoeos.jpg?size=50x50&set=set1', 'Expanded', '2020-03-26', 'active', 919876543, '621 School Trail', 14, '1988-04-03', 61);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cmclese3', 'cmclese3@sun.com', 'Charmian McLese', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/eiusullamassumenda.jpg?size=50x50&set=set1', 'eco-centric', '2020-03-30', 'active', 919876543, '67640 Fair Oaks Trail', 82, '1981-04-29', 89);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'clearoyde4', 'clearoyde4@google.ca', 'Cordelia Learoyde', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/delectussimiliquebeatae.jpg?size=50x50&set=set1', 'optimizing', '2020-03-09', 'active', 919876543, '1 Judy Crossing', 58, '1986-05-11', 23);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'elonding5', 'elonding5@chicagotribune.com', 'Ellynn Londing', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/atqueodiopraesentium.png?size=50x50&set=set1', 'matrix', '2020-03-26', 'active', 919876543, '23 Beilfuss Place', 69, '1989-02-06', 36);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'ageany6', 'ageany6@reverbnation.com', 'Abagael Geany', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/eosofficiisquia.png?size=50x50&set=set1', '4th generation', '2020-05-04', 'inactive', 919876543, '51297 Briar Crest Center', 22, '1997-05-24', 37);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'bsime7', 'bsime7@facebook.com', 'Brandi Sime', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quasimolestiaevoluptatum.bmp?size=50x50&set=set1', 'Compatible', '2020-03-19', 'active', 919876543, '31531 Knutson Terrace', 2, '1963-07-01', 5);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'ibelfelt8', 'ibelfelt8@cam.ac.uk', 'Inger Belfelt', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/impeditnobisvoluptatem.bmp?size=50x50&set=set1', 'multi-tasking', '2020-03-24', 'active', 919876543, '92980 Old Shore Crossing', 77, '1995-02-23', 1);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'klaban9', 'klaban9@webnode.com', 'Kippy Laban', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/beataequimolestias.png?size=50x50&set=set1', 'Team-oriented', '2020-03-22', 'active', 919876543, '54533 Sheridan Trail', 59, '1965-04-04', 26);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'iitzkovwitcha', 'iitzkovwitcha@simplemachines.org', 'Inge Itzkovwitch', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/aspernaturaccusantiumaut.jpg?size=50x50&set=set1', 'Re-engineered', '2020-03-24', 'active', 919876543, '7 Dunning Point', 84, '1972-03-02', 18);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'gibertb', 'gibertb@alibaba.com', 'Guss Ibert', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/namnecessitatibusaut.jpg?size=50x50&set=set1', 'Graphical User Interface', '2020-03-12', 'active', 919876543, '12744 Hallows Crossing', 30, '1963-12-31', 1);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'gzanninic', 'gzanninic@google.com.br', 'Gussi Zannini', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/accusamusnesciuntdeserunt.bmp?size=50x50&set=set1', 'pricing structure', '2020-03-28', 'active', 919876543, '379 Packers Trail', 43, '1984-01-21', 89);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mmarcumd', 'mmarcumd@xinhuanet.com', 'Marianne Marcum', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/minusaliquidconsequatur.bmp?size=50x50&set=set1', 'User-friendly', '2020-03-17', 'banned', 919876543, '18 Meadow Vale Drive', 16, '1962-01-11', 39);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cgovette', 'cgovette@weather.com', 'Clementina Govett', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/evenietmaximequos.bmp?size=50x50&set=set1', 'Versatile', '2020-03-22', 'active', 919876543, '2790 Anderson Point', 79, '1962-06-13', 41);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mruttgersf', 'mruttgersf@arstechnica.com', 'Maitilde Ruttgers', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/magnamvoluptatemvoluptatem.bmp?size=50x50&set=set1', 'projection', '2020-05-02', 'banned', 919876543, '51 Buell Junction', 1, '1982-04-23', 67);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jbreartong', 'jbreartong@naver.com', 'Javier Brearton', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/fugitetillo.bmp?size=50x50&set=set1', 'leading edge', '2020-03-11', 'active', 919876543, '012 Havey Parkway', 13, '1962-05-10', 3);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'wmonellih', 'wmonellih@xing.com', 'Wenonah Monelli', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/veniamrationequia.jpg?size=50x50&set=set1', 'maximized', '2020-03-14', 'active', 919876543, '9 Esch Place', 33, '1987-01-30', 90);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'pendrighii', 'pendrighii@slate.com', 'Pam Endrighi', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/erroreaqui.jpg?size=50x50&set=set1', 'dynamic', '2020-03-30', 'inactive', 919876543, '7 Thompson Drive', 63, '1991-07-05', 35);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cpinckardj', 'cpinckardj@dailymail.co.uk', 'Cletis Pinckard', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quaeratoptiorerum.png?size=50x50&set=set1', 'Customer-focused', '2020-03-11', 'active', 919876543, '4 Homewood Place', 7, '1978-11-10', 52);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'hgirauxk', 'hgirauxk@dyndns.org', 'Hewitt Giraux', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/eiusvoluptatemtenetur.jpg?size=50x50&set=set1', 'attitude-oriented', '2020-05-07', 'suspended', 919876543, '0237 Autumn Leaf Terrace', 15, '1976-02-09', 24);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'ksokilll', 'ksokilll@japanpost.jp', 'Kele Sokill', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/consequaturautqui.png?size=50x50&set=set1', 'transitional', '2020-03-18', 'suspended', 919876543, '5 2nd Drive', 61, '1993-03-06', 1);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jgorriem', 'jgorriem@biglobe.ne.jp', 'Jeramey Gorrie', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quaeratutsapiente.bmp?size=50x50&set=set1', 'regional', '2020-05-04', 'active', 919876543, '3 Petterle Road', 60, '1995-04-11', 83);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'gcroaln', 'gcroaln@vistaprint.com', 'Gael Croal', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/cumsapientesed.png?size=50x50&set=set1', 'Inverse', '2020-03-30', 'active', 919876543, '899 5th Alley', 73, '1996-10-30', 57);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'semmanueleo', 'semmanueleo@e-recht24.de', 'Starlene Emmanuele', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/doloresofficiisvel.png?size=50x50&set=set1', 'solution', '2020-03-21', 'banned', 919876543, '979 Union Terrace', 34, '1987-11-05', 79);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'nscarsbrickp', 'nscarsbrickp@geocities.jp', 'Nettie Scarsbrick', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/blanditiiserrorperferendis.jpg?size=50x50&set=set1', 'Future-proofed', '2020-03-30', 'active', 919876543, '64 Pawling Parkway', 16, '1970-05-27', 41);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'tcrierq', 'tcrierq@example.com', 'Thorsten Crier', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/nesciuntaliquamomnis.jpg?size=50x50&set=set1', 'Cross-group', '2020-03-22', 'active', 919876543, '62690 Butternut Circle', 67, '1986-12-09', 53);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cellcockr', 'cellcockr@china.com.cn', 'Caty Ellcock', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/velitexpeditadolorem.bmp?size=50x50&set=set1', 'Vision-oriented', '2020-03-21', 'active', 919876543, '200 Muir Pass', 26, '1964-04-13', 94);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'slanyons', 'slanyons@yolasite.com', 'Sammie Lanyon', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/enimquasirerum.bmp?size=50x50&set=set1', 'systemic', '2020-05-08', 'active', 919876543, '8066 Kinsman Street', 1, '1989-08-23', 11);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mtottmant', 'mtottmant@virginia.edu', 'Michel Tottman', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/perferendisautemnisi.bmp?size=50x50&set=set1', '24/7', '2020-03-13', 'active', 919876543, '54607 Katie Circle', 72, '1974-09-06', 41);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mraulstoneu', 'mraulstoneu@freewebs.com', 'Marne Raulstone', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quasiquasquos.png?size=50x50&set=set1', 'De-engineered', '2020-03-30', 'active', 919876543, '43 Sloan Point', 71, '1984-05-31', 6);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'lladsonv', 'lladsonv@mozilla.org', 'Lindy Ladson', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/veniameaquesunt.bmp?size=50x50&set=set1', 'function', '2020-03-29', 'banned', 919876543, '31153 Monument Hill', 86, '1966-05-05', 29);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'dwittw', 'dwittw@google.fr', 'De witt Mackstead', 'rmhG8SYqj', 'https://robohash.org/magniautalias.bmp?size=50x50&set=set1', 'matrices', '2020-03-26', 'active', 919876543, '9480 Lakeland Trail', 90, '1993-08-24', 66);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'hstrachanx', 'hstrachanx@ucoz.ru', 'Hettie Strachan', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/occaecatiautest.jpg?size=50x50&set=set1', 'leverage', '2020-03-27', 'active', 919876543, '63822 Kingsford Court', 46, '1976-08-09', 82);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'gparsonagey', 'gparsonagey@wunderground.com', 'Gunar Parsonage', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/etimpedittempore.jpg?size=50x50&set=set1', 'multi-tasking', '2020-03-29', 'active', 919876543, '51 7th Alley', 89, '1964-05-24', 30);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'fcodz', 'fcodz@adobe.com', 'Franni Cod', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/totamnamet.bmp?size=50x50&set=set1', 'Profit-focused', '2020-03-26', 'active', 919876543, '5 Harbort Parkway', 55, '1982-02-03', 77);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cvan10', 'cvan10@scribd.com', 'Carilyn Van den Velden', 'carbvVtRV', 'https://robohash.org/sitdolorumab.png?size=50x50&set=set1', 'reciprocal', '2020-03-20', 'active', 919876543, '074 6th Park', 8, '1981-11-14', 72);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'amcileen11', 'amcileen11@themeforest.net', 'Adelheid McIleen', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/autnihilmaiores.jpg?size=50x50&set=set1', 'cohesive', '2020-03-25', 'inactive', 919876543, '5836 Lyons Terrace', 62, '1982-03-28', 8);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'hpepi12', 'hpepi12@parallels.com', 'Harriett Pepi', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/officiisblanditiisvoluptatem.png?size=50x50&set=set1', 'concept', '2020-03-23', 'active', 919876543, '2 Kropf Lane', 80, '1988-09-10', 28);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cmcavin13', 'cmcavin13@netscape.com', 'Christiana McAvin', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quisintaut.bmp?size=50x50&set=set1', 'conglomeration', '2020-03-20', 'active', 919876543, '0751 Heffernan Alley', 41, '1980-10-20', 94);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'lbenoiton14', 'lbenoiton14@huffingtonpost.com', 'Lauretta Benoiton', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/exercitationemdoloremdolorem.png?size=50x50&set=set1', 'radical', '2020-05-08', 'active', 919876543, '571 High Crossing Plaza', 8, '1973-08-13', 99);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'bkilliner15', 'bkilliner15@digg.com', 'Bili Killiner', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/etamodi.png?size=50x50&set=set1', 'benchmark', '2020-03-27', 'active', 919876543, '3 Corscot Parkway', 100, '1984-12-15', 86);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cfishpoole16', 'cfishpoole16@boston.com', 'Conchita Fishpoole', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/errorbeataeaut.png?size=50x50&set=set1', 'Switchable', '2020-03-13', 'active', 919876543, '1869 Continental Hill', 29, '1999-04-19', 12);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'rmullin17', 'rmullin17@shutterfly.com', 'Rea Mullin', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/sedquiaea.jpg?size=50x50&set=set1', 'Advanced', '2020-03-10', 'active', 919876543, '43 Kropf Pass', 26, '1966-08-24', 6);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'blubeck18', 'blubeck18@homestead.com', 'Bathsheba Lubeck', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/repellatrerumqui.jpg?size=50x50&set=set1', 'Multi-layered', '2020-05-04', 'active', 919876543, '9 Glendale Alley', 28, '1979-09-13', 80);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jtotterdill19', 'jtotterdill19@independent.co.uk', 'Jonie Totterdill', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/perspiciatisitaquequibusdam.bmp?size=50x50&set=set1', 'Customizable', '2020-05-08', 'active', 919876543, '95 Victoria Terrace', 21, '1996-02-06', 54);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'rpinnocke1a', 'rpinnocke1a@163.com', 'Ricca Pinnocke', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/rerumporroaperiam.png?size=50x50&set=set1', 'Diverse', '2020-03-15', 'active', 919876543, '41243 Pankratz Trail', 72, '1995-06-12', 31);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jodoherty1b', 'jodoherty1b@arstechnica.com', 'Jeri O''Doherty', 'QjaGlYvVm6', 'https://robohash.org/autquiadeserunt.bmp?size=50x50&set=set1', 'alliance', '2020-03-09', 'active', 919876543, '2498 Lakewood Court', 78, '1979-12-05', 87);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'lsimoni1c', 'lsimoni1c@domainmarket.com', 'Lyle Simoni', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/sitnullahic.bmp?size=50x50&set=set1', 'tangible', '2020-03-12', 'active', 919876543, '10 Caliangt Terrace', 10, '1962-09-08', 12);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jstithe1d', 'jstithe1d@weather.com', 'Jecho Stithe', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/doloremolestiaeperspiciatis.png?size=50x50&set=set1', 'Self-enabling', '2020-03-10', 'active', 919876543, '50 Eagan Circle', 85, '1981-08-14', 66);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'oturney1e', 'oturney1e@cdbaby.com', 'Ophelie Turney', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/debitisplaceatiste.bmp?size=50x50&set=set1', 'systematic', '2020-03-10', 'active', 919876543, '1666 Anzinger Point', 9, '1987-12-14', 92);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mgrennan1f', 'mgrennan1f@blinklist.com', 'Magdalene Grennan', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/illuminautem.png?size=50x50&set=set1', 'Switchable', '2020-03-30', 'active', 919876543, '3397 Grayhawk Place', 20, '1997-01-23', 1);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'tjoannic1g', 'tjoannic1g@etsy.com', 'Tanya Joannic', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/etlaboreest.png?size=50x50&set=set1', 'monitoring', '2020-03-23', 'active', 919876543, '2 Bonner Point', 33, '1998-09-19', 15);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'dporte1h', 'dporte1h@discuz.net', 'Donall Porte', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/eaquiased.png?size=50x50&set=set1', 'fresh-thinking', '2020-05-02', 'inactive', 919876543, '595 Myrtle Trail', 100, '1968-02-10', 75);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'kcottle1i', 'kcottle1i@wikimedia.org', 'Knox Cottle', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/ametodioerror.png?size=50x50&set=set1', 'parallelism', '2020-03-23', 'active', 919876543, '532 Bay Place', 52, '1963-03-23', 35);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'nkilgallon1j', 'nkilgallon1j@icq.com', 'Nathanil Kilgallon', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/nullanonducimus.jpg?size=50x50&set=set1', 'forecast', '2020-05-08', 'inactive', 919876543, '55233 Badeau Lane', 92, '1999-07-20', 50);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'wbridgnell1k', 'wbridgnell1k@prnewswire.com', 'Wylie Bridgnell', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/expeditaquiiusto.png?size=50x50&set=set1', 'bottom-line', '2020-03-24', 'active', 919876543, '51886 Nova Crossing', 13, '1968-03-16', 11);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'fpeizer1l', 'fpeizer1l@microsoft.com', 'Ferdy Peizer', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/ipsaeumeum.bmp?size=50x50&set=set1', 'next generation', '2020-03-13', 'active', 919876543, '7 Ohio Center', 84, '1960-07-15', 52);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'fmundow1m', 'fmundow1m@about.me', 'Fanni Mundow', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/doloremsedconsequuntur.bmp?size=50x50&set=set1', 'logistical', '2020-03-21', 'banned', 919876543, '557 Luster Way', 48, '1983-12-02', 64);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jkidde1n', 'jkidde1n@kickstarter.com', 'Joscelin Kidde', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/facilisnesciuntqui.bmp?size=50x50&set=set1', 'Balanced', '2020-03-13', 'active', 919876543, '71 Glacier Hill Terrace', 71, '1975-09-15', 12);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'rsimunek1o', 'rsimunek1o@upenn.edu', 'Rocky Simunek', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/molestiaequasodit.jpg?size=50x50&set=set1', 'Innovative', '2020-05-07', 'active', 919876543, '0 Arrowood Park', 10, '1967-10-02', 57);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'slubeck1p', 'slubeck1p@wsj.com', 'Sidonia Lubeck', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/doloremqueaquia.png?size=50x50&set=set1', 'emulation', '2020-03-26', 'active', 919876543, '01 Dexter Crossing', 98, '1981-10-09', 57);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'atollit1q', 'atollit1q@infoseek.co.jp', 'Augusta Tollit', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/doloremvelitmagnam.bmp?size=50x50&set=set1', 'Seamless', '2020-03-20', 'active', 919876543, '27520 Fremont Alley', 97, '1981-09-16', 24);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'brenbold1r', 'brenbold1r@vimeo.com', 'Berri Renbold', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/placeatutaut.jpg?size=50x50&set=set1', 'empowering', '2020-05-08', 'banned', 919876543, '357 Park Meadow Place', 6, '1999-08-31', 88);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'bhamp1s', 'bhamp1s@canalblog.com', 'Bucky Hamp', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/cumquequisnihil.jpg?size=50x50&set=set1', 'Adaptive', '2020-03-27', 'active', 919876543, '6 Schmedeman Terrace', 73, '1997-02-15', 53);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'giacobassi1t', 'giacobassi1t@themeforest.net', 'Gallard Iacobassi', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/omnisnemoiure.jpg?size=50x50&set=set1', 'software', '2020-03-13', 'active', 919876543, '9 Cherokee Parkway', 44, '1989-07-23', 30);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jmyton1u', 'jmyton1u@psu.edu', 'Janna Myton', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/odioasperioresexplicabo.jpg?size=50x50&set=set1', 'artificial intelligence', '2020-05-07', 'active', 919876543, '99029 Myrtle Place', 38, '1976-02-05', 94);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'dallwood1v', 'dallwood1v@livejournal.com', 'Darya Allwood', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quaerataliquamconsequatur.jpg?size=50x50&set=set1', 'Progressive', '2020-05-08', 'active', 919876543, '87925 Buena Vista Place', 13, '1979-05-15', 5);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'ckennelly1w', 'ckennelly1w@microsoft.com', 'Cloris Kennelly', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/utdoloremvero.bmp?size=50x50&set=set1', 'bi-directional', '2020-03-30', 'active', 919876543, '93 Bowman Lane', 36, '1980-12-12', 79);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'thenryson1x', 'thenryson1x@stanford.edu', 'Tobie Henryson', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/utminustempora.png?size=50x50&set=set1', 'needs-based', '2020-03-12', 'active', 919876543, '42 Toban Road', 32, '1980-08-07', 64);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'cknocker1y', 'cknocker1y@paginegialle.it', 'Clement Knocker', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/utnesciuntsit.bmp?size=50x50&set=set1', 'motivating', '2020-05-07', 'inactive', 919876543, '452 Dayton Point', 32, '1990-09-09', 63);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'bcorderoy1z', 'bcorderoy1z@mail.ru', 'Bryanty Corderoy', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quoseaquelaboriosam.bmp?size=50x50&set=set1', 'functionalities', '2020-03-30', 'active', 919876543, '2607 Loftsgordon Point', 99, '1961-10-15', 35);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'csheepy20', 'csheepy20@google.com.br', 'Celestia Sheepy', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/numquamvoluptasofficia.bmp?size=50x50&set=set1', 'discrete', '2020-05-02', 'active', 919876543, '123 Waywood Trail', 46, '1974-03-02', 99);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'dbatkin21', 'dbatkin21@skype.com', 'Diane-marie Batkin', 'weX7snMSc', 'https://robohash.org/inciduntremmagnam.jpg?size=50x50&set=set1', 'dedicated', '2020-03-31', 'suspended', 919876543, '935 Fair Oaks Hill', 31, '1994-01-15', 44);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'sspatarul22', 'sspatarul22@feedburner.com', 'Sonnie Spatarul', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/istesitassumenda.bmp?size=50x50&set=set1', 'matrix', '2020-03-29', 'active', 919876543, '13 Superior Point', 44, '1987-12-17', 48);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'acoram23', 'acoram23@lulu.com', 'Adrianne Coram', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/ipsavoluptatesnesciunt.bmp?size=50x50&set=set1', 'infrastructure', '2020-05-03', 'active', 919876543, '9624 Nancy Plaza', 48, '1990-09-22', 53);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'ffeirn24', 'ffeirn24@time.com', 'Filbert Feirn', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/architectoomnisipsa.jpg?size=50x50&set=set1', 'Persistent', '2020-03-11', 'active', 919876543, '2 Shopko Avenue', 49, '1985-07-25', 71);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'wrubinow25', 'wrubinow25@hc360.com', 'Winnifred Rubinow', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/consecteturmodivoluptates.bmp?size=50x50&set=set1', 'Switchable', '2020-03-16', 'active', 919876543, '45 Fisk Lane', 88, '1961-01-03', 73);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mwhiteson26', 'mwhiteson26@stanford.edu', 'Milena Whiteson', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/rationefugaaut.jpg?size=50x50&set=set1', 'methodical', '2020-05-06', 'active', 919876543, '7 Russell Trail', 78, '1988-05-07', 72);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'vollander27', 'vollander27@cisco.com', 'Valeda Ollander', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/sedundeincidunt.bmp?size=50x50&set=set1', 'directional', '2020-03-20', 'active', 919876543, '39831 Barby Place', 6, '1994-09-28', 9);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'amorter28', 'amorter28@comsenz.com', 'Asia Morter', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/cumautporro.jpg?size=50x50&set=set1', 'discrete', '2020-03-28', 'active', 919876543, '63983 Farragut Court', 75, '1965-03-29', 82);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'twitnall29', 'twitnall29@webmd.com', 'Tawnya Witnall', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/autautvoluptas.png?size=50x50&set=set1', 'challenge', '2020-05-02', 'active', 919876543, '22 Quincy Alley', 2, '1974-02-12', 22);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'bbemlott2a', 'bbemlott2a@i2i.jp', 'Beryle Bemlott', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/consequaturnequeanimi.bmp?size=50x50&set=set1', 'analyzing', '2020-03-16', 'active', 919876543, '0 Rigney Center', 69, '1999-03-07', 81);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'shebble2b', 'shebble2b@sbwire.com', 'Sayer Hebble', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/recusandaeexpeditanihil.bmp?size=50x50&set=set1', 'Universal', '2020-05-04', 'active', 919876543, '87561 Northridge Alley', 5, '1991-03-10', 66);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mmckew2c', 'mmckew2c@java.com', 'Melantha McKew', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/iuretemporeconsequatur.png?size=50x50&set=set1', 'ability', '2020-03-17', 'active', 919876543, '460 Northview Parkway', 81, '1978-07-29', 92);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'kvanini2d', 'kvanini2d@imgur.com', 'Kristien Vanini', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/utdeseruntmollitia.jpg?size=50x50&set=set1', 'parallelism', '2020-03-13', 'suspended', 919876543, '7 Kennedy Plaza', 96, '1962-02-22', 42);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mrenneke2e', 'mrenneke2e@digg.com', 'Maryl Renneke', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/aliquamrepudiandaequos.jpg?size=50x50&set=set1', 'leverage', '2020-03-29', 'suspended', 919876543, '573 Blue Bill Park Road', 21, '1995-09-28', 30);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'mgreenall2f', 'mgreenall2f@wsj.com', 'Marianna Greenall', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/inciduntexercitationemomnis.bmp?size=50x50&set=set1', 'Quality-focused', '2020-03-14', 'suspended', 919876543, '3 Delaware Center', 26, '1990-10-27', 98);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'amoar2g', 'amoar2g@prnewswire.com', 'Alicea Moar', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/autquasrecusandae.png?size=50x50&set=set1', 'fault-tolerant', '2020-03-11', 'active', 919876543, '346 Golf View Alley', 23, '1961-11-24', 1);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'stimblett2h', 'stimblett2h@csmonitor.com', 'Sherill Timblett', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/liberoquamsed.jpg?size=50x50&set=set1', 'Intuitive', '2020-05-06', 'active', 919876543, '0 Waywood Road', 48, '1961-11-28', 50);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'ddaspar2i', 'ddaspar2i@fda.gov', 'Danella Daspar', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/blanditiiseumab.bmp?size=50x50&set=set1', 'maximized', '2020-03-26', 'active', 919876543, '41 Lien Crossing', 85, '1969-07-31', 81);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'ckopje2j', 'ckopje2j@netvibes.com', 'Cammi Kopje', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/laborumeiusut.png?size=50x50&set=set1', 'Fully-configurable', '2020-05-03', 'active', 919876543, '288 Barby Court', 44, '1965-10-12', 67);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'hkeddie2k', 'hkeddie2k@jiathis.com', 'Harvey Keddie', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/temporamolestiaequia.jpg?size=50x50&set=set1', 'Multi-lateral', '2020-03-20', 'active', 919876543, '8 Northwestern Avenue', 21, '1991-06-25', 31);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'aknoton2l', 'aknoton2l@zimbio.com', 'Archaimbaud Knoton', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/voluptatesistetemporibus.png?size=50x50&set=set1', 'user-facing', '2020-05-02', 'active', 919876543, '1687 North Court', 5, '1995-06-19', 72);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'bcarthew2m', 'bcarthew2m@about.com', 'Birgitta Carthew', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/admodia.bmp?size=50x50&set=set1', 'modular', '2020-03-30', 'active', 919876543, '6339 1st Point', 64, '1983-02-06', 86);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'dbeyn2n', 'dbeyn2n@seesaa.net', 'Demetria Beyn', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/quibusdamundecommodi.jpg?size=50x50&set=set1', 'info-mediaries', '2020-03-16', 'active', 919876543, '2377 Parkside Parkway', 28, '1980-09-16', 5);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'otreace2o', 'otreace2o@blogs.com', 'Ofelia Treace', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/adipiscicumquequi.png?size=50x50&set=set1', 'Diverse', '2020-03-18', 'active', 919876543, '6812 Almo Road', 18, '1997-08-24', 44);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'jdowry2p', 'jdowry2p@pen.io', 'Josie Dowry', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/etnostrumqui.jpg?size=50x50&set=set1', 'monitoring', '2020-05-04', 'active', 919876543, '9 Corben Point', 39, '1969-05-10', 51);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'vbrusby2q', 'vbrusby2q@ifeng.com', 'Velvet Brusby', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/expeditadignissimosveritatis.png?size=50x50&set=set1', 'synergy', '2020-05-05', 'banned', 919876543, '02 Claremont Circle', 70, '1996-02-24', 39);
INSERT INTO users ( username, email, name, password, photo, description, date_register, state_user, phone_number, address, id_postal, birth_date, total_votes) VALUES ( 'tgalloway2r', 'tgalloway2r@histats.com', 'Tades Galloway', '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS', 'https://robohash.org/autoditest.bmp?size=50x50&set=set1', 'solution-oriented', '2020-03-24', 'active', 919876543, '477 Mcbride Point', 64, '1960-12-23', 37);


 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-23', 'Juice - Orangina Chapéus de Sol', 'Colecção de 12 chapéus usados da marca Oragina. Campanha de maketing de 1996. ', '/images/oragina1_512x384.jpeg', 'active', 'collecting', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-19', 'Pack de garrafas - Vinagre Sherry', 'Um conjunto de garrafas artesanais concebido para Vinagre Sherry', '/images/vinegar1_512x384.jpeg', 'active', 'crafts', TRUE, 2);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-01', 'Tabuleiro para pequenas tartes', 'Ainda em perfeito estado. Comprei-o e não lhe dei uso, não faço tartes', '/images/tabuleiro1_512x384.jpeg', 'active', 'antiques', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-02', 'Livro técnico de Crab Brie', 'Livro do coznheiro artístico Greek, especializado em Crab Brie. Mais de 250 receitas, em bom estado', '/images/book1_512x384.jpeg', 'active', 'art', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-09', 'Clock Eating por Max Ernst', 'Poster de 1 metro por 2 metros do famoso artista Max Ernst. Qualidade de impressão elevada', '/images/art1_512x384.jpeg', 'active', 'art', TRUE, 45);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-18', 'Poster Glass por Max Ernst', 'Poster de 0,5m x1m do artista Max Ernst', '/images/art2_512x384.jpeg', 'active', 'art', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-07', 'Poster com pintura de Dali', 'Impressão de Alta qualidade Pintura do Salvador Dali 0.5x0.5', '/images/art3_512x384.jpeg', 'active', 'art', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-15', '6 pares de pousa copos da marca True North Strong Ale', 'Sem marcas de copos ou desvanecimento de imagem', '/images/colllections1_512x384.jpeg', 'active', 'collecting', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-26', 'Poster de obra desconhecida', 'Encontrei isto nas arrumações, ainda em bom estado porque foi bem guardado, artista desconhecido', '/images/art4_512x384.jpeg', 'active', 'art', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-07', 'Relógio e bracelete Visionary', 'Marca Visionary com cronómetro', '/images/watch1_512x384.jpeg', 'active', 'watches', TRUE, 8);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-11', 'Garden:pintura a óleo', '52x30cm', '/images/art5_512x384.jpeg', 'active', 'art', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-29', 'Le Cheve Noir', 'Quadro 30 por 70 cm, comprado online', '/images/art6_512x384.jpeg', 'active', 'art', TRUE, 6);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-26', 'Keyboard Logitech M12', 'Colors on keys, edition 2012, menos de 3 meses de uso', '/images/computer1_512x384.jpeg', 'active', 'computers', TRUE, 9);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-25', 'Logitech teclado+rato', 'Logitech Wireless Wave Combo MK550: ergonomic', '/images/computer2_512x384.jpeg', 'active', 'computers', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-26', 'Acer Monitor', 'Acer personal monitor. Demasiado grande para as minhas necessidades', '/images/computer3_512x384.jpeg', 'active', 'computers', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-23', 'Apple 3', 'Apple 3 inclui teclado e rato . Não inclui cabos ', '/images/computer4_512x384.jpeg', 'active', 'computers', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-09', 'Monitor Acer', 'Monitor acer inclui cabos, ergonomico', '/images/computer5_512x384.jpeg', 'active', 'computers', TRUE, 9);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-31', 'Sim Asus XM3343', 'Ecrã grande e muito agradável para quem goste de filmes e séries', '/images/computer6_512x384.jpeg', 'active', 'computers', TRUE, 5);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-31', 'Acoustic 5.1', 'Acoustic Audio AAT1000 Tower 5.1 Home Speaker System', '/images/computer7_512x384.jpeg', 'active', 'computers', TRUE, 5);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-04', 'Befree 2.1', 'Befree Sound 97095515M 2.1 Channel Surround Sound', '/images/computer8_512x384.jpeg', 'active', 'computers', TRUE, 6);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-07', 'Befree 5.1', 'Befree Sound 97095499M 5.1 Channel Surround Sound', '/images/computer9_512x384.jpeg', 'active', 'computers', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-21', 'zZounds All-Purpose Live Sound PA System', 'Não gosta dos seus vizinhos? Quer fazer a vida negra a eles? Este pack tem tdo o que você precisa', '/images/computer10_512x384.jpeg', 'active', 'computers', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-31', 'Venon Chapter50-100', 'Venon Origins, extra chapter, extra drawing scene, extra history behind his beggining', '/images/comics1_512x384.jpeg', 'active', 'comics', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-08', 'Superman and company 34', 'Chapter 45-57', '/images/comics2_512x384.jpeg', 'active', 'comics', TRUE, 7);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-14', 'Batman and company', 'Stories collecion behind Batmans friend', '/images/comics3_512x384.jpeg', 'active', 'comics', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-28', 'Constantine Comics', 'Coleccção 50 primeiros capítulos em bom estado.', '/images/comics4_512x384.jpeg', 'active', 'comics', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-23', 'Hellblazer Constantine', 'Hellbazer edição dos primeiros 25 capítulos, antes do acidente em Liverpool', '/images/comics5_512x384.jpeg', 'active', 'comics', TRUE, 8);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-03', 'Constantine Marvel n61', 'Constantine aparece no universo da marvel no capitulo 61', '/images/comics6_512x384.jpeg', 'active', 'comics', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-15', 'One piece chapter 25-30', 'Volume 7, capitulos de 25-30', '/images/comics7_512x384.jpeg', 'active', 'comics', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-26', 'Volume 23 - One piece', 'A história que junta Luffy com o seu pai', '/images/comics8_1_512x384.jpeg', 'active', 'comics', TRUE, 5);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-23', 'Sin City - detective história', 'Frank Miller oferece-nos um outro lado da sua Sin City. Livro com algum uso, edição de 1999', '/images/comics9_512x384.jpeg', 'active', 'comics', TRUE, 5);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-29', 'Histórias da velha Cidade -Sin city', 'Pack exclusivo de comics mais filme, ambos em bom estado. A comics tem a capa traseira vincada.', '/images/comics10_512x384.jpeg', 'active', 'comics', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-29', 'Blair Russell-Verão Pond na borda de um campo', 'Number 445 is an acrylic landscape painting by Blair Russell completed in 2019. This painting was made on an archival board.', '/images/art7_512x384.jpg', 'active', 'comics', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-23', 'Super Mario Bros Nintendo Ds', 'Novo em folha, versão coleccionador', '/images/video1.jpg', 'active', 'video_games', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-04', 'PS2 - Gladius', 'Gladuis ranking 98 na classificação videojogos.com com manual , 2003', '/images/video2.jpg', 'active', 'video_games', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-27', 'PS4 spider Man', 'Marvels Spiderman, jogo do ano, selado', '/images/video3.jpg', 'active', 'video_games', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-13', 'Resident Evil Ps4', 'Remake edição, 30 linguas disponíveis disco intacto e sem riscos', '/images/video4.jpg', 'active', 'video_games', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-22', 'Uncharted 2', 'Uncharted 2: entre os Ladrões-Game Of The Year Edition ( Sony PlayStation 3) M', '/images/video5.jpg', 'active', 'video_games', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-22', 'Desonrou', 'Game Of The Year Edition (Sony PlayStation 3, 2013) disco está em estado perfeito', '/images/video6.jpg', 'active', 'video_games', TRUE, 8);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-20', 'Uncharted:DF', 'Uncharted: Drakes Fortune Sony PlayStation 3, 2007 M', '/images/video7.jpg', 'active', 'video_games', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-27', 'Evil Within PS3', 'Evil Within (Sony PlayStation 3, 2014) disco está em estado perfeito', '/images/video8.jpg', 'active', 'video_games', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-13', 'Dead Space PS3', 'Dead Space-Jogo Playstation 3', '/images/video9.jpg', 'active', 'video_games', TRUE, 2);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-19', 'Sniper: Ghost Warrior 2', 'Sony PlayStation 3, 2013 disco está em estado perfeito', '/images/video10.jpg', 'active', 'video_games', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-17', 'Guitarra Electrica', '2010 Squier By Fender Guitarra Elétrica Cyclone Amarelo ICS09072789 Raro', '/images/guitar1.jpg', 'active', 'musical_instruments', TRUE, 4);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-02', 'Fender Guitarra', 'Squier Mini By Fender Guitarra', '/images/guitar2.jpg', 'active', 'musical_instruments', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-01', 'Guitarra Fibra Carbónica X40', '100% em fibra de carbono pela casa Violinos Electricos. Cor preta, liga trabalhada no Japão', '/images/guitar3.jpg', 'active', 'musical_instruments', TRUE, 6);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-04', 'Guitarra em Fibra X3451', 'Guitarra Elétrica Fibra De Carbono Na Cor Preta, robusta e muito leve, menos de 2 kilogramas', '/images/guitar4.jpg', 'active', 'musical_instruments', TRUE, 5);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-02', 'Guitarra com led', 'Luz Led Strat Trastes Guitarra Elétrica Luz Cristal Acrílico Corpo De Guitarra Violão', '/images/guitar5.jpg', 'active', 'musical_instruments', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-07', 'Game boy color', 'Usado e em bom estado, não tulizado nos útlimos anos, dois jogos incluidos: Tetris e Snake', '/images/elec1.jpg', 'active', 'electronics', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-05', 'RELOOP NEON pad', 'RELOOP NEON Pad Controller for Serato DJ 8x Touch Drum Pads RGB USB bus-powered oferecido e não usado', '/images/elec2.jpg', 'active', 'electronics', TRUE, 2);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-09', 'LCD Display 3.5', '3.5" TFT LCD Display RGB Display Module Kit Monitor 320x240 consumo de  12V tamanho: 3 cm x 2 cm x 0.3 cm  (CxLxA)', '/images/elec3.jpg', 'active', 'electronics', TRUE, 8);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-18', 'Cordão elástico', 'Cordão elástico Flat Branco 5mm 6mm 8mm 10mm 12mm Para Costura Costura Sob Medida', '/images/craft1.jpg', 'active', 'crafts', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-05', 'Jarros artesanais', '100 x diversos BOHO jarros artesanais feitos em madeira. Desenhos sul americanos', '/images/craft2.jpg', 'active', 'crafts', TRUE, 6);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-03', 'Pedra Preciosa Natural', 'Pedra Amazonita multicoloridos contas avulsas para fabricação de jóias 15"', '/images/craft3.jpg', 'active', 'crafts', TRUE, 5);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-30', 'Brocas de madeira rotativas', '6Pcs ferramentas rotativas Madeira Brocas Fresa mós Cortador Multi Pro Conjunto Broca Craft', '/images/craft4.jpg', 'active', 'crafts', TRUE, 8);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-25', 'Micro-brocas de madeira', '10x Micro Mini Bits Artes De Madeira Artesanato Hss Twist Drill Bit 0.6-2mm ferramentas rotativas', '/images/craft5.jpg', 'active', 'crafts', TRUE, 1);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-05-01', 'Caixa de arrumos', 'Caixa de madeira com fecho de 90 graus, ideal para arrumaçãode pequenas ferramentas e escovas', '/images/craft6.jpg', 'cancelled', 'crafts', TRUE, 7);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-27', 'Buraco de perfuração manual', 'Botão De Mão De Madeira Broca lidar com Broca americana Buraco Ferramentas Broca de perfuração', '/images/craft7.jpg', 'active', 'crafts', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-11', 'Pack de ferramentas manuais para escultura', '12pcs Kit De Ferramentas Escultura Em Madeira Aço Carbono Conjunto De Ferramentas Manuais cinzel Para Arte E Artesanato', '/images/craft8.jpg', 'active', 'crafts', TRUE, 8);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-26', 'Corte fundo COnkunto de 4', 'Aço de Carbono 4Pcs Trabalho Em Madeira Corte Cortador De Plug Power Broca ferramenta conjuntos Craft', '/images/craft9.jpg', 'active', 'crafts', TRUE, 3);
 INSERT INTO products ( date_placement, name_product, description, photo, state_product, category, is_new, id_owner) VALUES ( '2020-03-10', 'Aparfusadeira de 48 V', '48V Cordless Drill aprafusadeira electrica 35Nm multiusos bateria Li-ion', '/images/craft10.jpg', 'cancelled', 'crafts', TRUE, 4);




INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (1, '2020-07-08 19:10:25', 150);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (2, '2020-07-08 15:10:25', 125);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (3, '2020-07-08 16:10:25', 47);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (4, '2020-07-09 14:10:25', 73);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (5, '2020-07-08 13:10:25', 18);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (6, '2020-07-08 19:21:25', 143);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (7, '2020-07-08 19:32:25', 140);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (8, '2020-07-08 19:43:25', 148);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (9, '2020-07-23 19:54:25', 123);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (10, '2020-07-26 19:10:25', 62);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (11, '2020-07-25 20:54:25', 100);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (12, '2020-07-22 19:10:25', 91);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (13, '2020-07-29 19:10:25', 145);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (14, '2020-07-29 18:43:25', 141);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (15, '2020-07-11 19:43:25', 136);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (16, '2020-07-13 15:10:25', 89);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (17, '2020-07-16 15:32:25', 53);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (18, '2020-07-10 19:23:25', 20);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (19, '2020-07-26 15:10:25', 149);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (20, '2020-07-22 19:18:25', 28);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (21, '2020-07-26 19:17:25', 138);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (22, '2020-06-08 19:13:25', 149);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (23, '2020-06-30 19:10:25', 28);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (24, '2020-06-19 19:45:25', 37);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (25, '2020-06-26 19:45:25', 15);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (26, '2020-06-24 19:45:25', 132);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (27, '2020-06-30 19:45:25', 62);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (28, '2020-06-14 19:23:25', 103);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (29, '2020-06-27 19:22:25', 31);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (30, '2020-06-21 19:21:25', 113);
INSERT INTO buyitnows (id_buy, date_end, final_value) VALUES (31, '2020-06-10 19:11:25', 126);


INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (32, '2020-06-26', 39, 93);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (33, '2020-06-08', 11, 115);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (34, '2020-06-08', 20, 62);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (35, '2020-06-08', 37, 134);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (36, '2020-06-08', 46, 55);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (37, '2020-06-30', 41, 56);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (38, '2020-06-08', 3, 145);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (39, '2020-06-08', 15, 97);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (40, '2020-06-23', 12, 143);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (41, '2020-06-25', 29, 126);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (42, '2020-06-08', 20, 54);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (43, '2020-06-24', 18, 50);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (44, '2020-06-08', 43, 101);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (45, '2020-06-09', 44, 57);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (46, '2020-06-25', 5, 127);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (47, '2020-06-07', 12, 14);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (48, '2020-06-08', 24, 116);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (49, '2020-06-08', 1, 65);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (50, '2020-06-08', 6, 82);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (51, '2020-06-30', 35, 39);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (52, '2020-06-26', 22, 135);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (53, '2020-06-24', 42, 145);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (54, '2020-06-22', 47, 133);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (55, '2020-06-08', 21, 41);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (56, '2020-06-09', 28, 134);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (57, '2020-06-08', 49, 55);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (58, '2020-06-08', 7, 68);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (59, '2020-06-08', 42, 46);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (60, '2020-06-08', 15, 117);
INSERT INTO auctions (id_auction, date_end_auction, bidding_base, final_value) VALUES (61, '2020-06-15', 16, 115);


INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 52, 'do_nothing', 'done', 'nohing wrong', '2019-01-07', 'Balanced', 'grow visionary content', '2018-11-29', 52, 34);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 55, 'suspend', 'assume', 'acted as specified on article 5', '2018-12-10', 'system engine', 'whiteboard back-end deliverables', '2019-02-03', 24, 95);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 8, 'do_nothing', 'assumed', 'unlawful report', '2018-07-07', 'bottom-line', 'brand front-end paradigms', '2018-11-23', 87, 15);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 14, 'do_nothing', 'assumed', 'lawful comment', '2018-06-15', 'monitoring', 'grow B2B niches', '2019-01-28', 5, 3);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 25, 'ban', 'assumed', 'unlawful report', '2018-09-30', 'object-oriented', 'incentivize next-generation bandwidth', '2018-05-21', 30, 67);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 58, 'suspend', 'assumed', 'nude pic', '2019-01-10', 'interactive', 'strategize cross-media solutions', '2019-03-14', 54, 46);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 2, 'do_nothing', 'assume', 'nude pic', '2018-11-18', 'client-driven', 'synergize extensible portals', '2018-04-03', 69, 66);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 11, 'suspend', 'assume', 'acted as specified on article 5', '2018-08-01', 'actuating', 'scale cross-platform infomediaries', '2018-09-30', 63, 42);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 59, 'ban', 'assume', 'unlawful report', '2018-11-06', 'instruction set', 'transition dot-com methodologies', '2018-10-13', 63, 63);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 32, 'ban', 'assume', 'unlawful report', '2018-09-16', 'interactive', 'iterate frictionless architectures', '2018-11-08', 31, 57);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 6, 'suspend', 'done', 'nude pic', '2018-07-30', 'Front-line', 'strategize compelling vortals', '2019-01-02', 96, 1);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 10, 'do_nothing', 'done', 'lawful comment', '2018-07-22', 'initiative', 'engineer global architectures', '2019-03-17', 73, 38);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 1, 'suspend', 'assumed', 'lawful comment', '2018-09-27', 'cohesive', 'evolve seamless functionalities', '2019-03-27', 55, 35);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 64, 'ban', 'assumed', 'lawful comment', '2019-03-22', 'moderator', 'harness visionary niches', '2018-09-16', 55, 22);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 2, 'ban', 'done', 'lawful comment', '2018-07-06', 'Operative', 'engineer customized portals', '2019-01-03', 86, 72);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 41, 'suspend', 'assume', 'nude pic', '2019-02-03', 'uniform', 'incubate user-centric communities', '2018-12-22', 24, 64);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 80, 'suspend', 'assume', 'unlawful report', '2018-05-26', 'info-mediaries', 'whiteboard sexy functionalities', '2019-01-10', 27, 52);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 59, 'suspend', 'assumed', 'nothing wrong', '2018-07-12', 'Inverse', 'facilitate sticky architectures', '2018-11-09', 68, 94);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 34, 'suspend', 'done', 'lawful comment', '2018-11-07', 'cohesive', 'enhance ubiquitous synergies', '2018-11-18', 25, 68);
INSERT INTO reports (id_admin, id_punished, consequence, state_report, observation_admin, date_report, reason, text_report, date_begin_punishement, punishement_span, id_reporter) VALUES (7, 14, 'suspend', 'done', 'unlawful report', '2018-11-12', 'matrices', 'benchmark best-of-breed applications', '2019-01-25', 43, 43);

INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 56, 98, 13, 4, 3, '2020-06-02', 61.55);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 13, 60, 7, 5, 4, '2020-05-30', 23.51);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 23, 1, 8, 5, 4, '2020-06-02', 125.35);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 2, 7, 3, 3, 2, '2020-06-06', 33.02);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 41, 14, 22, 1, 5, '2020-05-09', 181.64);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 58, 1, 15, 2, 4, '2020-06-05', 125.43);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 15, 19, 14, 3, 3, '2020-06-03', 195.93);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 93, 68, 16, 5, 3, '2020-06-09', 196.78);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 35, 61, 8, 5, 4, '2020-05-30', 193.93);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 71, 10, 13, 3, 4, '2020-06-10', 34.73);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 27, 76, 19, 4, 3, '2020-06-07', 68.04);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 39, 2, 9, 5, 5, '2020-06-10', 187.97);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 2, 76, 5, 2, 5, '2020-06-10', 83.8);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 51, 31, 19, 3, 5, '2020-05-29', 67.88);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 54, 88, 4, 2, 1, '2020-06-09', 166.45);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 94, 56, 25, 4, 5, '2020-05-06', 40.69);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 79, 60, 27, 5, 5, '2020-05-09', 102.76);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 53, 98, 3, 2, 4, '2020-06-09', 182.79);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 3, 50, 23, 4, 2, '2020-05-11', 74.77);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 22, 83, 24, 4, 1, '2020-05-23', 44.26);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 87, 47, 19, 3, 5, '2020-05-30', 29.29);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 58, 33, 8, 3, 3, '2020-06-03', 87.35);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 36, 75, 20, 2, 1, '2020-05-09', 78.21);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 9, 80, 7, 4, 5, '2020-05-23', 139.28);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 58, 40, 10, 2, 4, '2020-05-21', 90.24);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 25, 83, 15, 4, 3, '2020-05-22', 197.17);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 9, 43, 21, 3, 4, '2020-05-09', 194.17);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 73, 20, 29, 1, 1, '2020-05-16', 135.84);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 74, 10, 18, 5, 5, '2020-05-25', 191.82);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 62, 49, 6, 4, 1, '2020-05-29', 169.23);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 41, 60, 12, 1, 3, '2020-06-05', 140.81);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 10, 83, 27, 4, 3, '2020-05-12', 169.9);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 33, 91, 7, 2, 3, '2020-05-27', 161.64);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 14, 53, 8, 5, 3, '2020-05-25', 125.95);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 17, 49, 12, 3, 1, '2020-05-22', 140.75);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 89, 49, 10, 5, 2, '2020-05-30', 102.87);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 1, 47, 21, 2, 2, '2020-05-27', 68.04);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 28, 29, 16, 5, 2, '2020-06-06', 68.46);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 33, 43, 14, 5, 5, '2020-05-27', 115.49);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 12, 60, 24, 5, 3, '2020-05-30', 137.08);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 27, 44, 5, 2, 4, '2020-05-24', 130.12);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 25, 1, 20, 1, 1, '2020-05-26', 127.59);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 79, 76, 12, 3, 2, '2020-06-02', 76.18);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 92, 33, 12, 5, 1, '2020-06-09', 70.56);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 52, 6, 8, 3, 1, '2020-05-30', 36.17);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 10, 15, 26, 2, 5, '2020-05-09', 77.68);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 35, 58, 16, 1, 2, '2020-06-09', 148.04);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 71, 5, 14, 1, 3, '2020-06-09', 153.54);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 51, 67, 28, 1, 3, '2020-05-16', 175.89);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 63, 1, 18, 1, 5, '2020-05-30', 28.35);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 100, 37, 8, 1, 3, '2020-06-03', 122.58);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 22, 62, 4, 5, 5, '2020-06-09', 86.22);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 7, 13, 19, 2, 2, '2020-06-07', 91.7);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 63, 84, 3, 2, 5, '2020-06-09', 159.03);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 23, 9, 11, 4, 4, '2020-06-02', 96.11);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 64, 76, 23, 2, 1, '2020-05-22', 142.65);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 37, 81, 1, 3, 1, '2020-05-24', 137.12);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 81, 40, 4, 2, 1, '2020-05-25', 21.2);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 51, 22, 4, 3, 1, '2020-06-09', 33.8);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 71, 34, 25, 2, 5, '2020-05-24', 140.86);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 83, 48, 6, 4, 1, '2020-05-30', 180.15);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 16, 72, 26, 2, 3, '2020-05-22', 141.21);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 50, 29, 8, 2, 4, '2020-05-30', 115.93);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 4, 7, 9, 4, 1, '2020-06-05', 100.17);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 85, 43, 27, 2, 1, '2020-05-22', 114.97);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 67, 93, 19, 3, 3, '2020-05-30', 136.74);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 3, 23, 9, 2, 1, '2020-06-05', 187.51);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 85, 56, 20, 2, 2, '2020-05-07', 106.48);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 51, 35, 22, 1, 5, '2020-05-30', 162.47);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 3, 40, 28, 3, 4, '2020-05-24', 66.33);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 18, 88, 6, 3, 5, '2020-06-08', 192.37);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 49, 82, 24, 2, 1, '2020-05-24', 171.2);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 52, 56, 12, 3, 2, '2020-05-21', 199.07);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 91, 26, 26, 1, 2, '2020-05-05', 155.02);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 58, 37, 16, 1, 2, '2020-05-23', 54.09);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 89, 33, 4, 4, 3, '2020-06-09', 197.94);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 86, 87, 10, 5, 2, '2020-05-29', 33.77);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 98, 1, 10, 1, 2, '2020-05-29', 65.58);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 68, 1, 6, 2, 4, '2020-05-22', 102.66);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 34, 59, 3, 4, 3, '2020-06-09', 98.52);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 6, 9, 8, 2, 2, '2020-06-05', 135.99);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 15, 19, 14, 1, 2, '2020-06-09', 154.61);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 27, 28, 18, 1, 2, '2020-05-21', 152.15);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 87, 33, 1, 3, 1, '2020-06-09', 189.32);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 38, 48, 28, 1, 4, '2020-05-24', 46.08);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 1, 50, 3, 1, 2, '2020-06-03', 121.54);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 76, 15, 7, 4, 3, '2020-05-29', 165.79);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 16, 33, 8, 5, 3, '2020-06-02', 177.93);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 45, 88, 1, 5, 5, '2020-05-27', 195.67);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 59, 51, 25, 3, 5, '2020-05-06', 36.99);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 92, 26, 17, 2, 5, '2020-05-27', 86.77);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 88, 18, 11, 1, 4, '2020-06-02', 132.26);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 50, 77, 23, 1, 3, '2020-05-11', 45.19);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 81, 90, 25, 4, 3, '2020-05-28', 28.43);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 99, 19, 27, 5, 5, '2020-06-03', 55.67);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 75, 93, 15, 5, 1, '2020-06-05', 172.94);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 41, 98, 9, 1, 2, '2020-06-05', 157.15);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 18, 92, 25, 2, 2, '2020-06-06', 129.69);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 24, 27, 12, 4, 5, '2020-06-09', 73.26);
INSERT INTO transactions ( id_buyer, id_seller, id, vote_inseller, vote_inbuyer, date_payment, value) VALUES ( 33, 56, 19, 2, 1, '2020-05-29', 85.82);

INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (1, 32, 68, 160, '2020-05-06');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (2, 33, 36, 73, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (3, 34, 76, 180, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (4, 37, 46, 123, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (5, 60, 40, 125, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (6, 57, 34, 98, '2020-05-04');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (7, 58, 42, 138, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (8, 58, 98, 133, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (9, 32, 56, 120, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (10, 33, 72, 116, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (11, 50, 99, 218, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (12, 57, 4, 302, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (13, 58, 55, 127, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (14, 59, 38, 129, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (15, 37, 78, 140, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (16, 42, 95, 140, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (17, 43, 95, 146, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (18, 47, 11, 122, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (19, 42, 63, 121, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (20, 46, 45, 316, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (21, 46, 52, 147, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (22, 44, 9, 135, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (23, 43, 20, 146, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (24, 57, 64, 123, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (25, 55, 7, 116, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (26, 58, 93, 129, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (27, 56, 70, 141, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (28, 52, 66, 131, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (29, 58, 12, 126, '2020-05-04');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (30, 33, 11, 124, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (31, 39, 23, 138, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (32, 37, 27, 138, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (33, 34, 51, 138, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (34, 37, 29, 141, '2020-03-31');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (35, 33, 26, 337, '2020-05-06');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (36, 38, 46, 115, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (37, 36, 77, 141, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (38, 38, 98, 140, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (39, 33, 63, 134, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (40, 46, 83, 139, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (41, 41, 7, 128, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (42, 45, 8, 117, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (43, 57, 9, 142, '2020-05-04');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (44, 42, 27, 116, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (45, 43, 88, 143, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (46, 48, 23, 305, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (47, 43, 21, 144, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (48, 42, 37, 326, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (49, 33, 49, 123, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (50, 47, 25, 143, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (51, 40, 81, 117, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (52, 49, 20, 140, '2020-03-30');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (53, 55, 52, 103, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (54, 48, 98, 150, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (55, 60, 52, 147, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (56, 43, 20, 143, '2020-03-31');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (57, 60, 15, 112, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (58, 50, 83, 135, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (59, 57, 12, 127, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (60, 41, 19, 122, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (61, 36, 23, 139, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (62, 58, 6, 303, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (63, 51, 81, 128, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (64, 41, 12, 139, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (65, 55, 67, 115, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (66, 58, 78, 203, '2020-05-06');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (67, 33, 48, 133, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (68, 35, 96, 115, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (69, 56, 36, 231, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (70, 41, 90, 310, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (71, 45, 87, 135, '2020-05-04');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (72, 46, 45, 106, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (73, 39, 88, 312, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (74, 57, 11, 137, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (75, 55, 38, 140, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (76, 37, 96, 148, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (77, 57, 91, 133, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (78, 46, 45, 111, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (79, 32, 39, 121, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (80, 47, 28, 114, '2020-03-30');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (81, 53, 9, 116, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (82, 55, 42, 136, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (83, 35, 33, 116, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (84, 36, 75, 123, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (85, 47, 24, 140, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (86, 58, 49, 117, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (87, 37, 81, 149, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (88, 48, 66, 148, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (89, 48, 21, 119, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (90, 47, 8, 143, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (91, 60, 76, 118, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (92, 38, 85, 105, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (93, 36, 5, 135, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (94, 34, 56, 135, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (95, 54, 61, 110, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (96, 37, 85, 105, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (97, 48, 15, 141, '2020-03-31');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (98, 48, 83, 346, '2020-05-08');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (99, 34, 31, 141, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (100, 38, 13, 101, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (101, 51, 13, 104, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (102, 56, 48, 116, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (103, 54, 61, 116, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (104, 57, 86, 214, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (105, 42, 87, 144, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (106, 37, 89, 134, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (107, 32, 7, 212, '2020-03-31');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (108, 52, 59, 121, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (109, 35, 19, 141, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (110, 37, 40, 148, '2020-05-07');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (111, 45, 62, 193, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (112, 50, 52, 317, '2020-05-03');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (113, 38, 2, 147, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (114, 56, 21, 135, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (115, 48, 23, 309, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (116, 47, 94, 116, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (117, 32, 96, 124, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (118, 60, 5, 105, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (119, 42, 54, 102, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (120, 52, 58, 130, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (121, 58, 25, 322, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (122, 33, 99, 134, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (123, 41, 94, 135, '2020-03-31');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (124, 54, 41, 120, '2020-05-06');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (125, 54, 35, 140, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (126, 43, 19, 119, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (127, 47, 12, 130, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (128, 45, 85, 120, '2020-05-05');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (129, 48, 43, 148, '2020-03-30');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (130, 40, 92, 118, '2020-05-02');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (131, 42, 66, 121, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (132, 33, 98, 108, '2020-05-06');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (133, 33, 61, 128, '2020-05-01');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (134, 36, 35, 128, '2020-05-09');
INSERT INTO biddings (id_bid, id_auction, bidder, value_bid, bidding_date) VALUES (135, 57, 35, 134, '2020-05-05');

INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (1, 95, 12, '2020-05-03', 'Proactive grid-enabled standardization', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (2, 75, 3, '2020-05-02', 'Robust value-added website', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (3, 30, 60, '2020-03-31', 'Adaptive directional installation', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (4, 34, 53, '2020-05-05', 'Operative well-modulated project', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (5, 90, 21, '2020-05-09', 'Exclusive empowering model', 15);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (6, 27, 21, '2020-05-07', 'Seamless multimedia access', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (7, 19, 10, '2020-05-06', 'Progressive human-resource strategy', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (8, 69, 15, '2020-05-07', 'Persevering clear-thinking software', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (9, 39, 15, '2020-05-02', 'Cross-platform attitude-oriented adapter', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (10, 16, 12, '2020-05-09', 'Diverse fresh-thinking migration', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (11, 85, 27, '2020-05-04', 'Up-sized full-range ability', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (12, 31, 10, '2020-05-09', 'Upgradable scalable superstructure', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (13, 78, 17, '2020-05-09', 'Operative next generation structure', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (14, 48, 2, '2020-05-03', 'Virtual zero tolerance help-desk', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (15, 50, 15, '2020-05-02', 'Distributed needs-based Graphic Interface', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (16, 13, 10, '2020-03-31', 'Networked empowering migration', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (17, 100, 19, '2020-05-09', 'Fully-configurable motivating customer loyalty', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (18, 74, 24, '2020-05-01', 'Inverse multi-tasking firmware', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (19, 67, 21, '2020-05-09', 'Cloned intermediate flexibility', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (20, 9, 26, '2020-05-03', 'Robust stable attitude', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (21, 81, 26, '2020-05-09', 'Down-sized high-level pricing structure', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (22, 17, 21, '2020-05-09', 'Virtual didactic collaboration', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (23, 8, 29, '2020-05-06', 'Customizable asynchronous budgetary management', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (24, 42, 16, '2020-05-01', 'Synchronised intermediate pricing structure', 14);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (25, 53, 60, '2020-05-07', 'Mandatory background model', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (26, 11, 11, '2020-05-09', 'Advanced even-keeled migration', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (27, 20, 15, '2020-05-06', 'Reverse-engineered content-based architecture', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (28, 84, 9, '2020-05-01', 'Self-enabling object-oriented customer loyalty', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (29, 37, 2, '2020-05-02', 'Self-enabling discrete data-warehouse', 15);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (30, 59, 14, '2020-03-31', 'Open-source client-driven policy', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (31, 65, 10, '2020-05-09', 'Compatible disintermediate architecture', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (32, 68, 13, '2020-05-06', 'Authenticated-centric hybrid benchmark', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (33, 66, 18, '2020-05-04', 'Centralized attitude-oriented system engine', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (34, 75, 21, '2020-05-09', 'Virtual incremental framework', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (35, 16, 12, '2020-05-02', 'Extended holistic orchestration', 7);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (36, 71, 21, '2020-05-04', 'Right-sized impactful implementation', 19);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (37, 34, 7, '2020-05-09', 'Grass-roots value-added orchestration', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (38, 14, 1, '2020-05-02', 'Seamless uniform hub', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (39, 29, 10, '2020-03-31', 'Devolved zero defect application', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (40, 10, 15, '2020-05-06', 'Persistent hybrid analyzer', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (41, 92, 6, '2020-05-06', 'Monitored zero tolerance productivity', 15);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (42, 36, 22, '2020-05-01', 'Reactive client-server open architecture', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (43, 17, 15, '2020-05-05', 'Sharable global structure', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (44, 62, 10, '2020-05-08', 'Adaptive fault-tolerant initiative', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (45, 70, 28, '2020-05-09', 'Extended asymmetric protocol', 19);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (46, 22, 29, '2020-05-06', 'Operative needs-based website', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (47, 87, 20, '2020-05-08', 'Versatile leading edge hardware', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (48, 31, 55, '2020-05-07', 'Operative systemic monitoring', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (49, 46, 32, '2020-05-08', 'Extended background system engine', 17);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (50, 51, 26, '2020-05-03', 'Realigned content-based encoding', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (51, 87, 60, '2020-03-31', 'Profit-focused real-time installation', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (52, 30, 27, '2020-05-05', 'Devolved bi-directional data-warehouse', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (53, 5, 29, '2020-05-09', 'Reverse-engineered 24 hour open system', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (54, 64, 6, '2020-05-03', 'Switchable multi-tasking application', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (55, 1, 19, '2020-05-02', 'Assimilated stable architecture', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (56, 4, 14, '2020-05-09', 'Automated motivating hub', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (57, 48, 19, '2020-05-04', 'Compatible logistical array', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (58, 52, 25, '2020-05-06', 'Authenticated-centric secondary array', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (59, 17, 14, '2020-05-08', 'Face to face optimal migration', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (60, 84, 24, '2020-05-09', 'Adaptive optimizing utilisation', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (61, 87, 28, '2020-05-09', 'Monitored methodical function', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (62, 40, 49, '2020-05-09', 'Fully-configurable empowering forecast', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (63, 8, 46, '2020-05-02', 'Optimized local internet solution', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (64, 63, 23, '2020-05-09', 'Open-source reciprocal infrastructure', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (65, 93, 24, '2020-05-09', 'Progressive contextually-based budgetary management', 7);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (66, 87, 14, '2020-05-09', 'Fundamental empowering throughput', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (67, 64, 14, '2020-05-04', 'Configurable real-time contingency', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (68, 30, 9, '2020-05-06', 'Proactive national local area network', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (69, 41, 17, '2020-05-05', 'Cloned systemic pricing structure', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (70, 48, 29, '2020-05-01', 'Pre-emptive grid-enabled throughput', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (71, 100, 27, '2020-05-09', 'De-engineered 5th generation concept', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (72, 91, 17, '2020-05-08', 'Total well-modulated flexibility', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (73, 66, 15, '2020-05-01', 'Synergistic high-level policy', 15);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (74, 28, 33, '2020-05-06', 'Polarised zero administration info-mediaries', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (75, 11, 17, '2020-05-09', 'Proactive full-range synergy', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (76, 38, 17, '2020-05-09', 'Function-based solution-oriented firmware', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (77, 7, 46, '2020-05-05', 'Polarised bifurcated task-force', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (78, 43, 13, '2020-05-01', 'Customizable homogeneous orchestration', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (79, 45, 21, '2020-05-08', 'Diverse didactic moratorium', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (80, 74, 14, '2020-05-02', 'Persevering bi-directional core', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (81, 42, 10, '2020-03-31', 'De-engineered 5th generation workforce', 14);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (82, 18, 16, '2020-05-09', 'De-engineered actuating model', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (83, 5, 22, '2020-05-07', 'Multi-tiered value-added info-mediaries', 19);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (84, 8, 7, '2020-05-09', 'Diverse zero tolerance installation', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (85, 81, 26, '2020-05-03', 'Customer-focused impactful focus group', 17);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (86, 95, 21, '2020-05-06', 'Multi-tiered next generation internet solution', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (87, 41, 46, '2020-05-06', 'Multi-layered static knowledge user', 19);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (88, 43, 24, '2020-05-08', 'Synergistic holistic paradigm', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (89, 68, 14, '2020-05-03', 'Right-sized disintermediate analyzer', 7);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (90, 49, 9, '2020-05-09', 'Managed tertiary standardization', 17);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (91, 14, 49, '2020-05-03', 'Optional context-sensitive circuit', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (92, 31, 29, '2020-03-31', 'Triple-buffered asynchronous orchestration', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (93, 88, 15, '2020-05-04', 'Front-line multimedia budgetary management', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (94, 33, 21, '2020-03-31', 'Seamless didactic paradigm', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (95, 17, 12, '2020-05-09', 'Fundamental multi-tasking open architecture', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (96, 80, 13, '2020-05-01', 'Programmable value-added budgetary management', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (97, 82, 19, '2020-05-03', 'Decentralized didactic open architecture', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (98, 98, 46, '2020-05-05', 'Vision-oriented 5th generation toolset', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (99, 17, 45, '2020-05-02', 'Re-contextualized executive strategy', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (100, 44, 39, '2020-05-03', 'Re-contextualized asynchronous open architecture', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (101, 29, 59, '2020-05-07', 'Robust static definition', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (102, 76, 21, '2020-05-05', 'Seamless high-level project', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (103, 29, 9, '2020-05-09', 'Realigned context-sensitive array', 19);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (104, 9, 26, '2020-03-31', 'Integrated 6th generation solution', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (105, 98, 22, '2020-05-09', 'Enhanced zero administration artificial intelligence', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (106, 81, 12, '2020-05-04', 'Operative value-added product', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (107, 73, 20, '2020-05-04', 'Optimized context-sensitive archive', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (108, 94, 40, '2020-05-06', 'Integrated demand-driven ability', 14);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (109, 95, 26, '2020-05-09', 'Diverse client-driven neural-net', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (110, 73, 25, '2020-05-01', 'Fundamental cohesive infrastructure', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (111, 27, 8, '2020-05-04', 'Enhanced executive middleware', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (112, 53, 13, '2020-05-06', 'Virtual 24/7 definition', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (113, 61, 13, '2020-05-08', 'Balanced full-range strategy', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (114, 53, 12, '2020-05-06', 'Ergonomic actuating superstructure', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (115, 22, 26, '2020-05-05', 'Team-oriented background Graphic Interface', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (116, 14, 25, '2020-05-09', 'Realigned composite capacity', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (117, 63, 58, '2020-05-04', 'Inverse real-time open architecture', 19);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (118, 49, 31, '2020-05-07', 'Streamlined attitude-oriented instruction set', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (119, 65, 19, '2020-05-04', 'Focused tertiary middleware', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (120, 88, 13, '2020-03-31', 'Stand-alone neutral conglomeration', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (121, 70, 13, '2020-05-07', 'Total multi-tasking hardware', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (122, 32, 27, '2020-03-31', 'Streamlined systemic instruction set', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (123, 19, 10, '2020-05-04', 'Secured 5th generation local area network', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (124, 6, 18, '2020-05-09', 'Integrated systemic knowledge base', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (125, 91, 28, '2020-05-09', 'Synergistic bandwidth-monitored encoding', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (126, 55, 11, '2020-05-05', 'Grass-roots neutral open system', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (127, 14, 28, '2020-05-04', 'Quality-focused user-facing neural-net', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (128, 49, 17, '2020-05-07', 'Cloned client-server benchmark', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (129, 28, 12, '2020-05-03', 'Pre-emptive multi-tasking workforce', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (130, 59, 29, '2020-05-08', 'Enhanced content-based toolset', 8);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (131, 12, 15, '2020-05-05', 'Fundamental object-oriented help-desk', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (132, 96, 51, '2020-05-08', 'Self-enabling intangible utilisation', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (133, 76, 22, '2020-05-02', 'Operative hybrid initiative', 17);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (134, 65, 52, '2020-05-03', 'Open-source didactic core', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (135, 10, 54, '2020-05-05', 'Focused 5th generation website', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (136, 9, 39, '2020-05-04', 'Face to face didactic instruction set', 14);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (137, 90, 54, '2020-03-31', 'Proactive logistical success', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (138, 55, 10, '2020-05-09', 'Expanded scalable customer loyalty', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (139, 81, 30, '2020-05-04', 'Upgradable systematic initiative', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (140, 55, 3, '2020-05-09', 'Exclusive secondary throughput', 19);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (141, 43, 55, '2020-05-09', 'Mandatory 5th generation capability', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (142, 87, 56, '2020-05-07', 'Sharable 4th generation capability', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (143, 45, 52, '2020-05-07', 'Multi-channelled modular installation', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (144, 65, 38, '2020-05-09', 'Exclusive global focus group', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (145, 36, 16, '2020-05-06', 'Customizable transitional matrices', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (146, 92, 23, '2020-05-07', 'Face to face system-worthy conglomeration', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (147, 38, 24, '2020-05-05', 'Multi-lateral fresh-thinking matrix', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (148, 3, 22, '2020-05-07', 'Profit-focused didactic help-desk', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (149, 16, 26, '2020-05-03', 'Proactive tangible service-desk', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (150, 73, 23, '2020-05-09', 'Cross-group mobile paradigm', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (151, 74, 31, '2020-05-07', 'Re-contextualized transitional focus group', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (152, 69, 38, '2020-05-09', 'Innovative clear-thinking system engine', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (153, 31, 35, '2020-05-08', 'Fundamental bandwidth-monitored moderator', 15);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (154, 31, 29, '2020-05-09', 'Synergized logistical forecast', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (155, 91, 14, '2020-05-09', 'Quality-focused methodical process improvement', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (156, 28, 24, '2020-05-09', 'Ameliorated even-keeled model', 7);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (157, 13, 21, '2020-03-31', 'Programmable bi-directional archive', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (158, 76, 21, '2020-05-01', 'Integrated upward-trending hierarchy', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (159, 38, 20, '2020-05-05', 'Horizontal analyzing algorithm', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (160, 39, 25, '2020-03-31', 'Profit-focused logistical product', 7);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (161, 87, 25, '2020-05-04', 'Phased asymmetric solution', 14);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (162, 84, 15, '2020-05-04', 'Devolved modular algorithm', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (163, 41, 22, '2020-05-08', 'Stand-alone real-time moratorium', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (164, 49, 18, '2020-05-04', 'Team-oriented local emulation', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (165, 85, 54, '2020-05-06', 'Progressive executive frame', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (166, 69, 19, '2020-05-04', 'Switchable transitional array', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (167, 92, 11, '2020-05-09', 'Decentralized zero defect instruction set', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (168, 26, 28, '2020-05-09', 'Synergized clear-thinking standardization', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (169, 88, 19, '2020-05-09', 'Future-proofed next generation middleware', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (170, 16, 10, '2020-05-02', 'Vision-oriented impactful array', 14);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (171, 85, 23, '2020-05-04', 'De-engineered asymmetric conglomeration', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (172, 62, 20, '2020-05-06', 'Customer-focused 4th generation project', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (173, 50, 22, '2020-05-09', 'Reverse-engineered radical concept', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (174, 20, 29, '2020-05-09', 'Exclusive local info-mediaries', 7);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (175, 12, 22, '2020-05-03', 'Multi-layered asymmetric functionalities', 14);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (176, 30, 27, '2020-05-01', 'Profound local forecast', 20);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (177, 24, 17, '2020-05-09', 'Vision-oriented bifurcated conglomeration', 4);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (178, 67, 22, '2020-05-05', 'Face to face needs-based paradigm', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (179, 41, 28, '2020-05-03', 'Expanded client-driven data-warehouse', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (180, 93, 22, '2020-05-08', 'Self-enabling eco-centric superstructure', 13);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (181, 95, 12, '2020-03-31', 'Down-sized heuristic application', 17);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (182, 80, 57, '2020-05-02', 'Enterprise-wide intangible standardization', 11);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (183, 40, 23, '2020-05-05', 'Total content-based service-desk', 5);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (184, 90, 24, '2020-05-09', 'Expanded next generation open architecture', 15);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (185, 62, 53, '2020-05-03', 'Switchable scalable open architecture', 9);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (186, 27, 26, '2020-05-05', 'Switchable scalable encoding', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (187, 18, 38, '2020-05-09', 'Team-oriented analyzing artificial intelligence', 17);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (188, 41, 13, '2020-05-04', 'Visionary bi-directional methodology', 16);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (189, 24, 25, '2020-05-01', 'Robust stable application', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (190, 52, 12, '2020-05-09', 'Compatible explicit frame', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (191, 79, 16, '2020-03-31', 'Networked methodical adapter', 17);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (192, 25, 14, '2020-05-08', 'Progressive interactive knowledge base', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (193, 85, 20, '2020-03-30', 'Enterprise-wide 5th generation pricing structure', 12);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (194, 23, 25, '2020-05-08', 'Open-architected zero defect portal', 10);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (195, 65, 11, '2020-05-04', 'Multi-channelled multi-state pricing structure', 18);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (196, 98, 22, '2020-05-09', 'Authenticated-friendly bandwidth-monitored collaboration', 3);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (197, 79, 54, '2020-05-09', 'Switchable multimedia project', 6);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (198, 98, 13, '2020-05-05', 'Pre-emptive 5th generation capacity', 1);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (199, 23, 35, '2020-05-01', 'Extended 24/7 middleware', 2);
INSERT INTO comments (id_comment, id_commenter, id, date_comment, msg_ofcomment, comment_likes) VALUES (200, 80, 27, '2020-05-09', 'Function-based object-oriented circuit', 3);

INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (1, 16, false, 'incentivize user-centric convergence', 'bid', 33, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (2, 46, false, 'facilitate revolutionary content', 'comment', NULL, 67);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (3, 13, false, 'deploy best-of-breed schemas', 'bid', NULL, 3);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (4, 11, false, 'revolutionize value-added e-business', 'bid', 38, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (5, 94, false, 'orchestrate 24/7 paradigms', 'comment', NULL, 17);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (6, 50, false, 'incubate dynamic niches', 'buy', NULL, 5);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (7, 73, true, 'disintermediate interactive architectures', 'end_of_auction', NULL, 60);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (8, 44, false, 'transition interactive web services', 'bid', NULL, 65);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (9, 42, false, 'strategize viral platforms', 'bid', NULL, 34);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (10, 51, false, 'engage back-end metrics', 'surpassed', 36,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (11, 81, false, 'maximize value-added e-commerce', 'surpassed', NULL, 57);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (12, 67, false, 'iterate B2B action-items', 'surpassed', 16, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (13, 55, false, 'benchmark intuitive metrics', 'bid', 9, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (14, 1, false, 'streamline revolutionary schemas', 'bid', NULL, 35);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (15, 96, false, 'synergize rich e-markets', 'bid', 9, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (16, 31, true, 'scale real-time systems', 'bid', 39, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (17, 35, false, 'grow real-time e-business', 'bid', NULL, 65);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (18, 40, true, 'extend world-class partnerships', 'bid', 41,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (19, 17, true, 'architect cross-media infrastructures', 'bid', 21, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (20, 98, false, 'synergize bricks-and-clicks synergies', 'surpassed', 22,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (21, 53, true, 'synergize innovative users', 'surpassed', NULL, 12);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (22, 99, false, 'monetize leading-edge e-tailers', 'surpassed', NULL, 16);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (23, 5, false, 'orchestrate wireless solutions', 'surpassed', 55, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (24, 26, true, 'monetize rich supply-chains', 'surpassed', 52, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (25, 63, true, 'morph out-of-the-box metrics', 'bid', NULL, 4);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (26, 75, true, 'exploit proactive technologies', 'bid', 54,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (27, 79, true, 'revolutionize visionary functionalities', 'bid', 1, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (28, 60, true, 'utilize cross-media communities', 'end_of_auction', 10, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (29, 29, false, 'incubate intuitive communities', 'bid', NULL, 32);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (30, 2, true, 'utilize front-end supply-chains', 'surpassed', NULL, 11);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (31, 75, true, 'orchestrate one-to-one web services', 'bid', 23, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (32, 10, true, 'target revolutionary methodologies', 'payment', 31,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (33, 33, false, 'integrate distributed content', 'bid', NULL, 29);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (34, 69, true, 'enhance revolutionary initiatives', 'bid', NULL, 33);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (35, 93, true, 'architect plug-and-play interfaces', 'bid', NULL, 26);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (36, 65, false, 'synthesize sticky experiences', 'bid', 7, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (37, 17, false, 'engage strategic initiatives', 'comment', 41,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (38, 68, false, 'expedite cross-platform eyeballs', 'payment', 1, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (39, 25, true, 'whiteboard back-end technologies', 'bid', NULL, 8);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (40, 41, true, 'scale cross-platform infomediaries', 'bid', 13,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (41, 17, false, 'redefine world-class convergence', 'bid', 7,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (42, 41, false, 'expedite cross-media solutions', 'bid', 42,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (43, 88, false, 'monetize best-of-breed e-commerce', 'comment', NULL, 18);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (44, 75, false, 'engage collaborative applications', 'surpassed', 51, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (45, 55, false, 'brand turn-key supply-chains', 'surpassed', 15, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (46, 83, false, 'generate seamless partnerships', 'surpassed', 55, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (47, 35, true, 'recontextualize real-time portals', 'surpassed', 40, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (48, 44, false, 'productize strategic channels', 'bid', 6, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (49, 75, true, 'unleash visionary eyeballs', 'comment', 9, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (50, 56, true, 'streamline efficient deliverables', 'bid', 18, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (51, 23, false, 'deploy strategic metrics', 'comment', NULL, 4);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (52, 46, true, 'strategize customized solutions', 'bid', NULL, 5);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (53, 45, true, 'syndicate viral e-commerce', 'comment', NULL, 10);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (54, 3, true, 'deliver dot-com synergies', 'payment', 34, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (55, 26, true, 'leverage turn-key e-markets', 'bid', 17, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (56, 80, true, 'target vertical initiatives', 'bid', 36, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (57, 21, false, 'empower holistic experiences', 'bid', NULL, 12);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (58, 26, false, 'target leading-edge systems', 'comment', 38,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (59, 65, false, 'implement robust applications', 'comment', 24,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (60, 1, false, 'innovate synergistic bandwidth', 'surpassed', NULL, 19);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (61, 18, true, 'syndicate killer e-markets', 'bid', 1, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (62, 83, true, 'e-enable intuitive metrics', 'surpassed', 54,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (63, 33, false, 'implement 24/7 applications', 'comment', 58, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (64, 56, true, 'innovate value-added partnerships', 'bid', NULL, 63);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (65, 60, true, 'enhance cross-media markets', 'bid', NULL, 16);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (66, 88, true, 'transition visionary schemas', 'surpassed', 16, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (67, 88, false, 'seize mission-critical interfaces', 'comment', 36,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (68, 61, false, 'deliver vertical paradigms', 'comment', 8, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (69, 55, false, 'engage holistic relationships', 'bid', NULL, 54);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (70, 18, false, 'synthesize frictionless e-commerce', 'comment', 24, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (71, 86, false, 'whiteboard bricks-and-clicks mindshare', 'bid', 47, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (72, 89, true, 'brand dot-com relationships', 'comment', 39, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (73, 84, false, 'leverage killer communities', 'comment', NULL, 18);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (74, 36, false, 'unleash seamless vortals', 'buy', 41, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (75, 21, true, 'architect transparent synergies', 'bid', NULL, 12);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (76, 75, false, 'utilize front-end models', 'bid', 15,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (77, 63, false, 'mesh strategic e-business', 'bid', NULL, 16);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (78, 30, true, 'grow 24/7 functionalities', 'bid', NULL, 18);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (79, 97, false, 'integrate one-to-one deliverables', 'comment', NULL, 41);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (80, 24, true, 'unleash collaborative networks', 'surpassed', 8, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (81, 37, true, 'optimize collaborative schemas', 'comment', 16,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (82, 58, true, 'redefine end-to-end networks', 'surpassed', NULL, 11);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (83, 12, true, 'innovate collaborative initiatives', 'comment', 17,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (84, 83, true, 'leverage dot-com synergies', 'comment', 15, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (85, 58, true, 'strategize innovative content', 'bid', 53, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (86, 50, true, 'streamline front-end interfaces', 'buy', 58, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (87, 9, false, 'harness distributed models', 'bid', 32, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (88, 3, false, 'aggregate synergistic portals', 'surpassed', 1,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (89, 77, true, 'matrix impactful ROI', 'end_of_auction', NULL, 13);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (90, 67, false, 'incentivize clicks-and-mortar platforms', 'bid', 8,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (91, 33, false, 'brand one-to-one convergence', 'surpassed', 26, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (92, 80, false, 'benchmark impactful channels', 'bid', 6, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (93, 10, true, 'evolve frictionless action-items', 'end_of_auction', NULL, 17);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (94, 87, false, 'exploit one-to-one eyeballs', 'comment', 16, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (95, 28, true, 'incubate transparent convergence', 'surpassed', 53,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (96, 77, true, 'strategize out-of-the-box niches', 'buy', 5,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (97, 62, false, 'cultivate user-centric convergence', 'payment', NULL, 22);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (98, 23, false, 'orchestrate sexy channels', 'bid', NULL, 8);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (99, 34, false, 'generate intuitive paradigms', 'bid', NULL, 20);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (100, 44, true, 'maximize collaborative experiences', 'surpassed', NULL, 28);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (101, 20, false, 'embrace integrated convergence', 'bid', 6, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (102, 90, false, 'repurpose collaborative infrastructures', 'bid', 47, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (103, 18, true, 'scale cutting-edge architectures', 'surpassed', NULL, 6);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (104, 65, true, 'synthesize magnetic ROI', 'surpassed', 16, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (105, 25, true, 'synergize global ROI', 'bid', 31, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (106, 46, false, 'cultivate B2C convergence', 'surpassed', NULL, 7);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (107, 86, false, 'architect granular systems', 'bid', NULL, 28);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (108, 5, true, 'synthesize synergistic action-items', 'buy', NULL, 21);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (109, 37, false, 'maximize out-of-the-box web services', 'surpassed', 18, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (110, 49, false, 'synthesize robust metrics', 'surpassed', NULL, 23);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (111, 62, true, 'disintermediate bleeding-edge e-business', 'bid', 55, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (112, 15, false, 'iterate granular communities', 'bid', 35, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (113, 63, true, 'iterate intuitive architectures', 'comment', 33, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (114, 74, true, 'engineer distributed applications', 'bid', NULL, 11);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (115, 17, false, 'extend ubiquitous infomediaries', 'payment', 17,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (116, 28, false, 'aggregate integrated architectures', 'bid', NULL, 3);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (117, 16, false, 'maximize front-end portals', 'bid', 19,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (118, 74, true, 'innovate proactive web-readiness', 'payment', 11, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (119, 35, true, 'engineer B2B ROI', 'surpassed', NULL, 33);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (120, 64, false, 'integrate visionary platforms', 'surpassed', 39,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (121, 39, true, 'engage granular networks', 'bid', NULL, 24);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (122, 19, false, 'orchestrate 24/365 content', 'surpassed', NULL, 64);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (123, 37, true, 'engage seamless action-items', 'bid', 16, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (124, 38, true, 'strategize transparent e-business', 'bid', NULL, 29);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (125, 22, true, 'architect value-added content', 'surpassed', 43,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (126, 4, false, 'innovate synergistic e-markets', 'surpassed', NULL, 19);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (127, 15, true, 'seize B2C architectures', 'surpassed', 31, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (128, 63, true, 'enable plug-and-play architectures', 'comment', 28,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (129, 93, false, 'monetize real-time systems', 'surpassed', NULL, 38);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (130, 79, false, 'drive front-end niches', 'bid', NULL, 41);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (131, 80, true, 'enhance interactive e-services', 'surpassed', 49, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (132, 58, false, 'aggregate seamless eyeballs', 'comment', 19, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (133, 88, true, 'integrate integrated e-commerce', 'bid', NULL, 9);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (134, 76, false, 'brand enterprise methodologies', 'comment', 9,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (135, 7, true, 'leverage revolutionary solutions', 'comment', NULL, 61);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (136, 32, true, 'morph value-added technologies', 'comment', NULL, 3);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (137, 6, true, 'productize magnetic communities', 'bid', 17, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (138, 55, true, 'productize dynamic models', 'comment', 4, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (139, 37, true, 'integrate bleeding-edge synergies', 'bid', 40, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (140, 17, true, 'unleash next-generation paradigms', 'surpassed', NULL, 21);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (141, 13, true, 'implement proactive convergence', 'bid', 48, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (142, 75, false, 'recontextualize 24/365 eyeballs', 'surpassed', NULL, 17);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (143, 13, true, 'benchmark dot-com relationships', 'bid', NULL, 24);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (144, 41, true, 'syndicate cross-platform applications', 'comment', 12, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (145, 77, true, 'deploy out-of-the-box architectures', 'bid', 26, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (146, 27, false, 'exploit front-end web services', 'bid', 52, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (147, 37, false, 'optimize B2C solutions', 'bid', NULL, 54);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (148, 43, true, 'deploy robust web-readiness', 'surpassed', NULL, 28);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (149, 62, false, 'syndicate global networks', 'surpassed', 47, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (150, 52, false, 'optimize intuitive experiences', 'surpassed', 30,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (151, 20, false, 'redefine virtual e-business', 'bid', 33, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (152, 46, true, 'reinvent clicks-and-mortar channels', 'bid', 43, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (153, 33, false, 'drive transparent experiences', 'bid', NULL, 39);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (154, 64, false, 'matrix global action-items', 'bid', 40, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (155, 59, true, 'architect global e-tailers', 'bid', 36, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (156, 92, false, 'maximize mission-critical infrastructures', 'bid', NULL, 51);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (157, 55, true, 'reinvent virtual users', 'comment', NULL, 8);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (158, 39, true, 'enable cross-platform platforms', 'bid', NULL, 7);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (159, 33, false, 'transition bleeding-edge e-tailers', 'comment', 11, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (160, 64, true, 'extend virtual relationships', 'bid', 29, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (161, 26, false, 'strategize virtual networks', 'comment', 58, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (162, 20, true, 'cultivate web-enabled e-services', 'comment', 14, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (163, 73, true, 'redefine next-generation infomediaries', 'comment', NULL, 24);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (164, 42, true, 'iterate holistic e-markets', 'bid', 17, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (165, 53, false, 'recontextualize distributed platforms', 'bid', NULL, 3);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (166, 21, false, 'leverage intuitive e-markets', 'surpassed', 13, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (167, 47, false, 'iterate compelling portals', 'bid', 50, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (168, 11, true, 'transition vertical experiences', 'surpassed', NULL, 37);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (169, 19, false, 'deliver interactive communities', 'comment', NULL, 36);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (170, 82, false, 'disintermediate front-end e-services', 'comment', 19, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (171, 46, true, 'transition bleeding-edge ROI', 'bid', NULL, 45);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (172, 17, true, 'transform extensible supply-chains', 'bid', NULL, 47);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (173, 57, false, 'syndicate efficient e-commerce', 'bid', 9, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (174, 80, false, 'evolve granular bandwidth', 'bid', 41, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (175, 33, false, 'expedite magnetic e-business', 'bid', 42, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (176, 95, true, 'exploit turn-key models', 'surpassed', 8, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (177, 85, true, 'synthesize global niches', 'comment', 35, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (178, 38, false, 'grow cross-platform vortals', 'bid', 12, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (179, 94, true, 'harness one-to-one e-markets', 'surpassed', 44, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (180, 99, false, 'empower virtual architectures', 'comment', 14, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (181, 13, false, 'strategize sticky relationships', 'surpassed', 9, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (182, 70, true, 'synthesize virtual e-commerce', 'bid', NULL, 58);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (183, 77, false, 'target innovative relationships', 'bid', 25, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (184, 68, false, 'exploit end-to-end platforms', 'comment', 38,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (185, 27, false, 'synergize e-business markets', 'bid', 29, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (186, 47, true, 'e-enable one-to-one systems', 'payment', NULL, 11);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (187, 32, true, 'reintermediate best-of-breed portals', 'comment', NULL, 60);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (188, 10, true, 'evolve scalable convergence', 'surpassed', NULL, 17);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (189, 56, false, 'strategize holistic synergies', 'comment', NULL, 18);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (190, 9, true, 'utilize proactive content', 'bid', 22, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (191, 94, false, 'transition turn-key platforms', 'comment', 5, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (192, 58, true, 'synthesize next-generation markets', 'bid', 33, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (193, 3, false, 'empower frictionless relationships', 'payment', 43, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (194, 80, false, 'revolutionize open-source initiatives', 'bid', NULL, 35);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (195, 7, false, 'visualize sexy channels', 'bid', 47, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (196, 98, false, 'aggregate seamless e-commerce', 'bid', 6, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (197, 83, true, 'enhance collaborative niches', 'surpassed', 31,NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (198, 75, true, 'drive next-generation metrics', 'bid', NULL, 29);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (199, 55, true, 'visualize visionary infrastructures', 'buy', 13, NULL);
INSERT INTO notifications (id_notif, id_user, is_new, text_notification, type_ofnotification, id_item, id_comment) VALUES (200, 67, true, 'reintermediate seamless networks', 'bid', 23, NULL);

INSERT INTO report_users (id_report, id_user) VALUES (2, 11);
INSERT INTO report_users (id_report, id_user) VALUES (5, 14);
INSERT INTO report_users (id_report, id_user) VALUES (8, 82);
INSERT INTO report_users (id_report, id_user) VALUES (11, 69);
INSERT INTO report_users (id_report, id_user) VALUES (14, 74);
INSERT INTO report_users (id_report, id_user) VALUES (17, 85);

INSERT INTO report_products (id_report, id_product) VALUES (3, 18);
INSERT INTO report_products (id_report, id_product) VALUES (6, 60);
INSERT INTO report_products (id_report, id_product) VALUES (9, 30);
INSERT INTO report_products (id_report, id_product) VALUES (12, 52);
INSERT INTO report_products (id_report, id_product) VALUES (15, 8);
INSERT INTO report_products (id_report, id_product) VALUES (18, 60);

INSERT INTO report_comments (id_report, id_comment) VALUES (1, 183);
INSERT INTO report_comments (id_report, id_comment) VALUES (4, 103);
INSERT INTO report_comments (id_report, id_comment) VALUES (7, 86);
INSERT INTO report_comments (id_report, id_comment) VALUES (10, 73);
INSERT INTO report_comments (id_report, id_comment) VALUES (13, 199);
INSERT INTO report_comments (id_report, id_comment) VALUES (16, 68);
INSERT INTO report_comments (id_report, id_comment) VALUES (19, 106);
INSERT INTO report_comments (id_report, id_comment) VALUES (20, 105);









--------TEMPLATE
/*
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR
);
*/


DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;

CREATE TABLE cards (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  user_id INTEGER REFERENCES users NOT NULL
);

CREATE TABLE items (
  id SERIAL PRIMARY KEY,
  card_id INTEGER NOT NULL REFERENCES cards ON DELETE CASCADE,
  description VARCHAR NOT NULL,
  done BOOLEAN NOT NULL DEFAULT FALSE
);
/*
INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'john@example.com',
  '$2b$10$R4RTYmhvcZA88wXtT0Kux.J8pJ1J24urtp.mqiDET/gVR0nwvJAbS'
); -- Password is 1234. Generated using Hash::make('1234')
*/
INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');



----TRIGGERS


CREATE OR REPLACE FUNCTION id_sale_auction_not_equal_verifier()
RETURNS trigger AS $BODY$
BEGIN
IF EXISTS (select 1 FROM auctions WHERE id_auction = new.id_buy) 
    THEN RAISE EXCEPTION 'Invalid buy_it_now, same id as auction';
ELSE 
    RETURN NEW;
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER id_sale_auction_not_equal BEFORE INSERT ON buyitnows
FOR EACH ROW EXECUTE PROCEDURE id_sale_auction_not_equal_verifier();

CREATE OR REPLACE FUNCTION date_end_buy_greater_product_verifier()
RETURNS trigger AS $BODY$

DECLARE
    start_date date;
BEGIN
SELECT date_placement INTO start_date
FROM products
WHERE id = new.id_buy;

IF new.date_end >= start_date
THEN
  RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid Buy it now, date end less than product placement date';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER date_end_buy_greater_product BEFORE INSERT ON buyitnows
FOR EACH ROW EXECUTE PROCEDURE  date_end_buy_greater_product_verifier();



CREATE OR REPLACE FUNCTION delete_auction_verifier()
RETURNS trigger AS $BODY$

BEGIN
IF OLD.date_end_auction > current_date + interval '2 days'
    THEN RETURN OLD;
ELSE 
  RAISE EXCEPTION 'Cant delete auction when less than 48hrs remain for it to end';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER delete_auction BEFORE DELETE ON auctions
FOR EACH ROW EXECUTE PROCEDURE  delete_auction_verifier();

CREATE OR REPLACE FUNCTION id_auction_sale_not_equal_verifier()
RETURNS trigger AS $BODY$
BEGIN
IF EXISTS (select 1 FROM buyitnows WHERE id_buy = new.id_auction) 
    THEN RAISE EXCEPTION 'Invalid auction, same id as buy_it_now';
ELSE 
    RETURN NEW;
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER id_auction_sale_not_equal BEFORE INSERT ON auctions
FOR EACH ROW EXECUTE PROCEDURE id_auction_sale_not_equal_verifier();

CREATE OR REPLACE FUNCTION product_exists_verifier()
RETURNS trigger AS $BODY$
BEGIN
IF EXISTS (select 1 FROM products WHERE id = new.id_auction) 
    THEN RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid auction, product doesnt exist';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER product_exists BEFORE INSERT ON auctions
FOR EACH ROW EXECUTE PROCEDURE  product_exists_verifier();



CREATE OR REPLACE FUNCTION end_greater_begin_auction()
RETURNS trigger AS $BODY$

DECLARE
    product_date date;
BEGIN
SELECT date_placement INTO product_date FROM products WHERE id=new.id_auction;
IF new.date_end_auction > product_date THEN
  RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid auction date';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER end_greater_begin BEFORE INSERT OR UPDATE ON auctions 
FOR EACH ROW EXECUTE PROCEDURE  end_greater_begin_auction();


CREATE OR REPLACE FUNCTION last_minute_bid_extender_verifier()
RETURNS trigger AS $BODY$
DECLARE
     minuteDiff int;
BEGIN
 PERFORM FLOOR(
    (EXTRACT(EPOCH FROM (auction.date_end_auction::timestamp - new.bidding_date::timestamp)))/60) as minuteDiff FROM auctions;
IF minuteDiff <= 1
    THEN UPDATE auctions SET date_end_auction = date_end_auction + interval '5 minutes' WHERE id_auction = new.id_auction;
END IF;
RETURN NULL;
END;
$BODY$
LANGUAGE 'plpgsql';

/*
CREATE TRIGGER last_minute_bid_extender BEFORE INSERT ON biddings
FOR EACH ROW EXECUTE PROCEDURE  last_minute_bid_extender_verifier();
*/

CREATE OR REPLACE FUNCTION auction_bidder_not_owner_verifier()
RETURNS trigger AS $BODY$
DECLARE
    owner integer;
BEGIN
SELECT id_owner INTO owner FROM products WHERE new.id_auction = id;
IF owner != new.bidder
    THEN RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid bid, owner cant bid in his auction';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER auction_bidder_not_owner BEFORE INSERT ON biddings
FOR EACH ROW EXECUTE PROCEDURE  auction_bidder_not_owner_verifier();

CREATE OR REPLACE FUNCTION bid_date_less_end_greater_begin_verifier()
RETURNS trigger AS $BODY$

DECLARE
    start_date date;
    end_date date;
BEGIN
SELECT date_placement INTO start_date
FROM products
WHERE id = new.id_auction;

SELECT date_end_auction INTO end_date
FROM auctions
WHERE id_auction = new.id_auction;

IF new.bidding_date >= start_date AND new.bidding_date <= end_date  THEN
  RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid bid, date outside range';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER bid_date_less_end_greater_begin BEFORE INSERT ON biddings
FOR EACH ROW EXECUTE PROCEDURE  bid_date_less_end_greater_begin_verifier();



--COMENTADO PORQUE INTERFERE NA COMPRA DIRECTA-- ARRANJAR MODO DE SÓ SER APLICADO NOS LEILOES = BEFORE INSERT ON transactions (e fôr AUCTIOn....)
-- CREATE OR REPLACE FUNCTION end_auction_notif_verifier()
-- RETURNS trigger AS $BODY$
-- DECLARE
--     product_name text;
--     id_bidders integer[];
--     id integer;
-- BEGIN
-- id_bidders := ARRAY(
-- SELECT bidder
-- FROM biddings
-- WHERE id_auction = new.id_auction
-- );
-- SELECT name_product INTO product_name FROM product WHERE new.id_auction = id_product;
--  FOREACH id IN ARRAY id_bidders
--   LOOP
--     INSERT INTO notification VALUES (DEFAULT,id,true,'auction for product ' || name_product || ' ended','end_of_auction',new.id_auction,NULL);
--   END LOOP;
-- RETURN NEW;
-- END;
-- END IF;
-- $BODY$
-- LANGUAGE 'plpgsql';
-- CREATE TRIGGER end_auction_notif BEFORE INSERT ON transactions
-- FOR EACH ROW EXECUTE PROCEDURE  end_auction_notif_verifier();





CREATE OR REPLACE FUNCTION bid_greater_highest_verifier()
RETURNS trigger AS $BODY$

DECLARE
    max_value integer;
BEGIN
SELECT final_value INTO max_value
FROM auctions 
WHERE id_auction = new.id_auction;
IF max_value IS NOT NULL AND new.value_bid > max_value THEN
  RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid bid amount needs to be greater than highest bid';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER bid_greater_highest BEFORE INSERT ON biddings
FOR EACH ROW EXECUTE PROCEDURE  bid_greater_highest_verifier();




CREATE OR REPLACE FUNCTION payment_notif_verifier()
RETURNS trigger AS $BODY$
DECLARE
    owner integer;
    id_highest_bidder integer;
    product_name text;
BEGIN
SELECT name_product INTO product_name FROM products WHERE new.id = id;
INSERT INTO notifications VALUES (DEFAULT,new.id_buyer,true,'Bought item ' || name_product,'buy',new.id,NULL);
INSERT INTO notifications VALUES (DEFAULT,new.id_seller,true,'Payment for product ' || name_product || ' received','payment',new.id,NULL);
RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER payment_notif AFTER UPDATE ON transactions FOR EACH ROW EXECUTE PROCEDURE payment_notif_verifier();

CREATE OR REPLACE FUNCTION votes_seller_updater()
RETURNS trigger AS $BODY$

DECLARE
    status state_product;
BEGIN
/*for seller*/
    IF old.vote_inSeller IS NOT NULL AND old.vote_inSeller != new.vote_inSeller
        THEN
        UPDATE users SET total_votes = total_votes - old.vote_inSeller + new.vote_inSeller WHERE id = new.id_seller;
    END IF;
    IF old.vote_inSeller IS NULL AND new.vote_inSeller IS NOT NULL
        THEN
        UPDATE users SET total_votes = total_votes + new.vote_inSeller WHERE id = new.id_seller;
    END IF;
/*for buyer*/
    IF old.vote_inBuyer IS NOT NULL AND old.vote_inBuyer != new.vote_inBuyer
        THEN
        UPDATE users SET total_votes = total_votes - old.vote_inBuyer + new.vote_inBuyer WHERE id = new.id_buyer;
    END IF;
    IF old.vote_inBuyer IS NULL AND new.vote_inBuyer IS NOT NULL
        THEN
        UPDATE users SET total_votes = total_votes + new.vote_inBuyer WHERE id = new.id_buyer;
    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER update_votes_seller AFTER UPDATE ON transactions
FOR EACH ROW EXECUTE PROCEDURE  votes_seller_updater();

CREATE OR REPLACE FUNCTION transaction_members_not_same_verifier()
RETURNS trigger AS $BODY$
BEGIN
IF new.id_buyer != new.id_seller 
    THEN RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid transactions buyer = seller ';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER transaction_members_not_same BEFORE INSERT ON transactions
FOR EACH ROW EXECUTE PROCEDURE  transaction_members_not_same_verifier();


CREATE OR REPLACE FUNCTION transaction_members_exist_verifier()
RETURNS trigger AS $BODY$
BEGIN
IF EXISTS (select 1 FROM users WHERE users.id = new.id_buyer ) 
AND EXISTS (select 1 FROM users WHERE users.id = new.id_seller )
AND EXISTS (select 1 FROM products WHERE products.id = new.id )  
    THEN RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid transaction, member/s dont exist';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER transaction_members_exist BEFORE INSERT ON transactions
FOR EACH ROW EXECUTE PROCEDURE  transaction_members_exist_verifier();



CREATE OR REPLACE FUNCTION notif_commenters_verifier()
RETURNS trigger AS $BODY$
DECLARE
    product_name text;
    id_commenters integer[];
    id_cmtr integer;
BEGIN
id_commenters := ARRAY(
SELECT id_commenter
FROM comments
WHERE id = new.id /*[TODO]id_product = new.id_product*/
);
SELECT name_product INTO product_name FROM products WHERE new.id = id;
 FOREACH id_cmtr IN ARRAY id_commenters
  LOOP
    INSERT INTO notifications VALUES (DEFAULT,id_cmtr,true,'new comment for ' || product_name,'comment',new.id,NULL);
  END LOOP;
RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql';

CREATE TRIGGER notif_commenters BEFORE INSERT ON comments FOR EACH ROW EXECUTE PROCEDURE notif_commenters_verifier();

CREATE OR REPLACE FUNCTION date_comment_greater_product_verifier()
RETURNS trigger AS $BODY$

DECLARE
    start_date date;
BEGIN
SELECT date_placement INTO start_date
FROM products
WHERE id = new.id;

IF new.date_comment >= start_date
THEN
  RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid Comment date less than product placement date';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER date_comment_greater_product BEFORE INSERT ON comments
FOR EACH ROW EXECUTE PROCEDURE  date_comment_greater_product_verifier();



CREATE OR REPLACE FUNCTION report_user_members_not_same_verifier()
RETURNS trigger AS $BODY$
DECLARE
    idReporter integer;
BEGIN
SELECT id_reporter INTO idReporter FROM reports WHERE id=new.id_report;
IF new.id_user != idReporter 
    THEN RETURN NEW;
ELSE 
  RAISE EXCEPTION 'Invalid report reporter = reported ';
END IF;
END;
$BODY$
LANGUAGE 'plpgsql';


CREATE TRIGGER report_user_members_not_same BEFORE INSERT ON report_users
FOR EACH ROW EXECUTE PROCEDURE  report_user_members_not_same_verifier();



CREATE OR REPLACE FUNCTION product_search_update_ff() RETURNS TRIGGER AS $$
BEGIN
IF TG_OP = 'INSERT' THEN
  NEW.search = setweight(to_tsvector('portuguese', NEW.name_product), 'A') || 
               setweight(to_tsvector('portuguese', NEW.description), 'B');
END IF;
IF TG_OP = 'UPDATE' THEN
  IF NEW.name_product <> OLD.name_product OR NEW.description<> OLD.description THEN
    NEW.search = setweight(to_tsvector('portuguese', NEW.name_product), 'A') || 
                 setweight(to_tsvector('portuguese', NEW.description), 'B');
  END IF;
END IF;
RETURN NEW;
END
$$ LANGUAGE 'plpgsql';
CREATE TRIGGER product_search_update BEFORE INSERT OR UPDATE ON products FOR EACH ROW EXECUTE PROCEDURE product_search_update_ff();


CREATE OR REPLACE FUNCTION date_transaction_auction_restrictions_verifier()
RETURNS trigger AS $BODY$
DECLARE
    end_date date;
    payment_end_date date;
BEGIN
    SELECT date_end_auction INTO end_date FROM auctions WHERE id_auction = new.id_auction;
    SELECT date_end INTO end_date FROM buyitnows WHERE id_buy = new.id_buy;
    payment_end_date := end_date + interval '2 days';
    IF(new.id_auction != NULL)
    THEN
        IF new.date_payment != NULL 
        AND new.date_payment >= end_date
        AND new.date_payment <= payment_end_date
        THEN
        RETURN NEW;
        ELSE 
        RAISE EXCEPTION 'Invalid auction transaction payment date not within accepted interval';
        END IF;
    ELSE
        IF new.date_payment != NULL 
        AND new.date_payment >= end_date
        AND new.date_payment <= payment_end_date
        THEN
        RETURN NEW;
        ELSE 
        RAISE EXCEPTION 'Invalid buyitnow transaction payment date not within accepted interval';
        END IF;
    END IF;
END;
$BODY$
LANGUAGE 'plpgsql';
CREATE TRIGGER date_transaction_auction_restrictions BEFORE UPDATE ON transactions FOR EACH ROW EXECUTE PROCEDURE date_transaction_auction_restrictions_verifier();


-- CREATE OR REPLACE FUNCTION date_transaction_sale_restrictions_verifier()
-- RETURNS trigger AS $BODY$
-- DECLARE
--     end_date date;
--     payment_end_date date;
-- BEGIN
--     SELECT date_end INTO end_date FROM buyitnows WHERE id_buy = new.id;
--     payment_end_date := end_date + INTERVAL '2 days';
--     IF new.date_payment >= end_date
--     AND new.date_payment <= payment_end_date
--     THEN
--     RETURN NEW;
--     ELSE 
--     RAISE EXCEPTION '2_Invalid transactions payment date not within accepted interval';
--     END IF;
-- END;
-- $BODY$
-- LANGUAGE 'plpgsql';
-- CREATE TRIGGER date_transaction_sale_restrictions BEFORE UPDATE ON transactions FOR EACH ROW EXECUTE PROCEDURE date_transaction_sale_restrictions_verifier();


CREATE OR REPLACE FUNCTION update_deleted_account_func()
RETURNS TRIGGER AS $BODY$
BEGIN
    IF new.state_user = 'inactive'
        THEN
        UPDATE users SET username='Account Erased', email=users.id::text, 
        name=users.id::text, password=users.id::text, photo=NULL,  description=NULL, phone_number=0, address=users.id::text, id_postal=NULL,
        birth_date= to_date('19000101','YYYYMMDD'), total_votes=0 WHERE id=new.id;   
    END IF;
    RETURN NEW;
END;
$BODY$ LANGUAGE plpgsql;
CREATE TRIGGER update_deleted_account AFTER UPDATE OF state_user ON users FOR EACH ROW EXECUTE PROCEDURE update_deleted_account_func();



CREATE OR REPLACE FUNCTION bid_surpassed_notif_verifier()
    RETURNS trigger AS $BODY$
    DECLARE
        owner integer;
        id_highest_bidder integer;
        h_v integer;
    BEGIN
    SELECT id_owner INTO owner FROM products WHERE new.id_auction = id;
    SELECT final_value INTO h_v FROM auctions WHERE id_auction = new.id_auction;
    SELECT bidder INTO id_highest_bidder FROM biddings WHERE id_auction = new.id_auction AND value_bid = h_v;
    INSERT INTO notifications VALUES (DEFAULT,owner,true,'New bid','bid',new.id_auction,NULL);
    INSERT INTO notifications VALUES (DEFAULT,id_highest_bidder,true,'bid surpassed','surpassed',new.id_auction,NULL);
    RETURN NEW;
    END;
    $BODY$
    LANGUAGE 'plpgsql';


CREATE TRIGGER bid_surpassed_notif BEFORE INSERT ON biddings FOR EACH ROW EXECUTE PROCEDURE bid_surpassed_notif_verifier();



SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
SELECT setval('admins_id_admin_seq', (SELECT MAX(id_admin) FROM admins));
SELECT setval('products_id_seq', (SELECT MAX(id) FROM products));
SELECT setval('biddings_id_bid_seq', (SELECT MAX(id_bid) FROM biddings));
SELECT setval('transactions_id_transac_seq', (SELECT MAX(id_transac) FROM transactions));
SELECT setval('comments_id_comment_seq', (SELECT MAX(id_comment) FROM comments));
SELECT setval('notifications_id_notif_seq', (SELECT MAX(id_notif) FROM notifications));
SELECT setval('reports_id_seq', (SELECT MAX(id) FROM reports));


