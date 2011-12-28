%{
/*

Poker Watcher

Modo de uso en UNIX:

flex poker.l &&
gcc lex.yy.c -o poker -lfl &&
sudo tcpdump -lni eth1 -As 0 port 9339 | ./poker

*/

char count;
char pot[5];
char pindex;

void print_card(int v);

%}

/*Tags de HTML*/

FLOP_INI                    "flop%"
FLOP_END                    "%."

/*Otros tipos de datos*/

WHITESPACE                  [ \t]+
RETURN                      [\n]+
TEXTWORD                    [^ \t\n<>&]+
DIGIT                       [-+]*[0-9]+
JUNK                        .|\n

/*Contextos*/

%s FLOP

%%

{FLOP_INI}                  {
                                BEGIN(FLOP);
                                pindex=0;
                                count=0;
                                printf("flop:");
                            }
<FLOP>{DIGIT}               {
                                if(count++%2)
                                    print_card((pot[pindex++] = atoi(yytext)));
                            }
<FLOP>{FLOP_END}            { printf("\--------------\n"); BEGIN(INITIAL); }



{JUNK}

%%

void print_card(int v) {
    if(v>10) {
        char h;
        switch(v) {
            case 11: h='J'; break;
            case 12: h='Q'; break;
            case 13: h='K'; break;
            case 14: h='A'; break;
            default: h='e';
        }
        printf(" %c", h);
    }
    else
        printf(" %i", v);
}

int main() {
    yylex();
    
    return 0;
}
