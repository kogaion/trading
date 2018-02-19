# trading
=======

A Symfony project created on February 3, 2018, 12:02 pm.


1. extract from Tradeville
    1. portfolio
1. more Portfolios for the same symbol
1. YTM
1. add commission
1. compare view bonds with inflation
1. Currency Facade
1. BondsBundle
1. SharesBundle


# check -> actiuni din fonduri/dividende care nu exista in shares_screener
SELECT * FROM `fonds_symbols` left join shares_screener using (symbol) where shares_screener.id is null
SELECT * from dividends left join shares_screener using (symbol) where shares_screener.id is null


# prezenta simbolurilor in fonds + randamentul dividendelor lor

SELECT 
shares_screener.symbol, 
if(dividends.id is null, '-', concat(round(dividends.value / shares_screener.ask * 100, 2),'%')) as dividend_randament, 
concat(round(shares_screener.variation,2), '%') as price_variation, 
shares_screener.screen_date, 
group_concat(fonds.risk) as fonds_risk, 
group_concat(round(fonds_symbols.ratio,2), '% ') as fonds_composition,
group_concat(if(fonds_evolution.id is null, '-', concat(round(fonds_evolution.evolution, 2), '% '))) as fonds_randament,
shares_screener.ask as share_price, 
ifnull(dividends.value, '-') as dividend_price, 
group_concat(fonds.id) as fonds
FROM fonds_symbols 
inner join (
select * from (    
select * from shares_screener
order by shares_screener.screen_date desc, shares_screener.stamp desc 
)shares_screener
group by shares_screener.symbol) shares_screener on shares_screener.symbol = fonds_symbols.symbol
left join fonds on fonds_symbols.fond = fonds.id
left join dividends on dividends.symbol = shares_screener.symbol
left join fonds_evolution on fonds_evolution.fond = fonds.id and fonds_evolution.interval = ',-,P1Y'
group by shares_screener.symbol
order by dividends.profit_ratio desc, price_variation asc


De scos evolutia actiunii pe last 1Y (ca sa aproximez si cu dividendul)

# Componenta fondurilor si evolutia lor lunara

SELECT 
fonds.name, 
group_concat(concat(fonds_symbols.symbol, ': ', round(fonds_symbols.ratio, 2))) as components, 
round(evo_1m.evolution, 2) as evo_1m,
round(evo_1y.evolution, 2) as evo_1y
from fonds 
inner join fonds_symbols on fonds.id = fonds_symbols.fond
left join fonds_evolution evo_1m on evo_1m.fond = fonds.id and evo_1m.interval = ',-,P1M'
left join fonds_evolution evo_1y on evo_1y.fond = fonds.id and evo_1y.interval = ',-,P1Y'
group by fonds.id